"""
Trade Importer — Main entry point.
Fetches trades from connected brokers and posts them to Laravel API.

Usage:
    python -m workers.trade_importer.main

Environment variables:
    LARAVEL_API_URL — Base URL for internal API (e.g., https://ami.com/api/internal)
    LARAVEL_API_KEY — Journal API key
    METAAPI_TOKEN   — (optional) MetaApi token for MetaTrader connections
"""

import logging
import sys

from workers.common.api_client import LaravelApiClient
from workers.trade_importer.binance_fetcher import fetch_binance_trades
from workers.trade_importer.metaapi_fetcher import fetch_metatrader_trades, is_available as metaapi_available

logging.basicConfig(
    level=logging.INFO,
    format="%(asctime)s [%(levelname)s] %(name)s: %(message)s",
)
logger = logging.getLogger("trade_importer")


def main():
    logger.info("=== Trade Importer starting ===")

    try:
        client = LaravelApiClient()
    except ValueError as e:
        logger.error(f"Configuration error: {e}")
        sys.exit(1)

    # 1. Health check
    try:
        health = client.health_check()
        if health.get("status") != "ok":
            logger.error(f"API health check failed: {health}")
            sys.exit(1)
        logger.info("API health check: OK")
    except Exception as e:
        logger.error(f"API health check failed: {e}")
        sys.exit(1)

    # 2. Fetch and import Binance trades
    try:
        binance_connections = client.get_connections(conn_type="binance")
        logger.info(f"Found {len(binance_connections)} Binance connection(s)")

        for conn in binance_connections:
            try:
                entries = fetch_binance_trades(conn)
                if entries:
                    result = client.post_entries_batched(entries)
                    logger.info(
                        f"Binance conn {conn['id']}: "
                        f"created={result['created']}, "
                        f"duplicates={result['duplicates_skipped']}, "
                        f"errors={len(result['errors'])}"
                    )
                    client.update_sync_status(
                        conn["id"],
                        success=True,
                        trades_imported=result["created"],
                    )
                else:
                    logger.info(f"Binance conn {conn['id']}: no new trades")
                    client.update_sync_status(conn["id"], success=True)
            except Exception as e:
                error_msg = str(e)[:500]
                logger.error(f"Binance conn {conn['id']} failed: {error_msg}")
                client.update_sync_status(
                    conn["id"], success=False, error=error_msg
                )
    except Exception as e:
        logger.error(f"Error fetching Binance connections: {e}")

    # 3. MetaTrader (if MetaApi token is available)
    if metaapi_available():
        try:
            mt_types = ["metatrader4", "metatrader5"]
            for mt_type in mt_types:
                connections = client.get_connections(conn_type=mt_type)
                logger.info(f"Found {len(connections)} {mt_type} connection(s)")

                for conn in connections:
                    try:
                        entries = fetch_metatrader_trades(conn)
                        if entries:
                            result = client.post_entries_batched(entries)
                            logger.info(
                                f"{mt_type} conn {conn['id']}: "
                                f"created={result['created']}"
                            )
                            client.update_sync_status(
                                conn["id"],
                                success=True,
                                trades_imported=result["created"],
                            )
                        else:
                            client.update_sync_status(conn["id"], success=True)
                    except Exception as e:
                        error_msg = str(e)[:500]
                        logger.error(f"{mt_type} conn {conn['id']} failed: {error_msg}")
                        client.update_sync_status(
                            conn["id"], success=False, error=error_msg
                        )
        except Exception as e:
            logger.error(f"Error with MetaTrader connections: {e}")
    else:
        logger.info("MetaApi: METAAPI_TOKEN not set, skipping MetaTrader import")

    logger.info("=== Trade Importer finished ===")


if __name__ == "__main__":
    main()
