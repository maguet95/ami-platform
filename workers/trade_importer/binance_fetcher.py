"""
Binance trade fetcher.
Fetches spot and futures trades using read-only API keys.
"""

import logging
from datetime import datetime, timezone

from binance.client import Client as BinanceClient
from binance.exceptions import BinanceAPIException

logger = logging.getLogger(__name__)


def fetch_binance_trades(connection: dict) -> list[dict]:
    """
    Fetch trades from Binance for a given connection.
    Returns list of entries in Laravel API format.
    """
    creds = connection.get("credentials", {})
    api_key = creds.get("api_key", "")
    api_secret = creds.get("api_secret", "")
    user_id = connection["user_id"]

    if not api_key or not api_secret:
        logger.error(f"Connection {connection['id']}: missing Binance credentials")
        return []

    client = BinanceClient(api_key, api_secret)
    entries = []

    # Fetch spot trades
    entries.extend(_fetch_spot_trades(client, user_id, connection))

    # Fetch futures trades
    entries.extend(_fetch_futures_trades(client, user_id, connection))

    return entries


def _fetch_spot_trades(client: BinanceClient, user_id: int, connection: dict) -> list[dict]:
    """Fetch spot trades from Binance."""
    entries = []

    try:
        # Get active trading pairs from account
        account = client.get_account()
        balances = [b for b in account.get("balances", []) if float(b["free"]) > 0 or float(b["locked"]) > 0]
        symbols = set()

        for balance in balances:
            asset = balance["asset"]
            if asset in ("USDT", "BUSD", "BNB", "BTC", "ETH"):
                continue
            for quote in ("USDT", "BUSD"):
                symbols.add(f"{asset}{quote}")

        # Also check recent trades for common pairs
        common_pairs = ["BTCUSDT", "ETHUSDT", "BNBUSDT", "SOLUSDT", "ADAUSDT", "DOGEUSDT", "XRPUSDT"]
        symbols.update(common_pairs)

        for symbol in symbols:
            try:
                trades = client.get_my_trades(symbol=symbol, limit=500)
                for trade in trades:
                    direction = "long" if trade["isBuyer"] else "short"
                    price = float(trade["price"])
                    qty = float(trade["qty"])
                    fee = float(trade["commission"])
                    timestamp = datetime.fromtimestamp(
                        trade["time"] / 1000, tz=timezone.utc
                    ).isoformat()

                    entries.append({
                        "user_id": user_id,
                        "external_id": f"binance_spot_{trade['id']}",
                        "symbol": symbol,
                        "market": "crypto_spot",
                        "direction": direction,
                        "entry_price": price,
                        "exit_price": price,
                        "quantity": qty,
                        "pnl": 0,  # Spot trades don't have direct PnL
                        "fee": fee,
                        "opened_at": timestamp,
                        "closed_at": timestamp,
                        "duration_seconds": 0,
                        "status": "closed",
                        "source": "binance_spot",
                    })
            except BinanceAPIException as e:
                if e.code != -1121:  # Invalid symbol - skip silently
                    logger.warning(f"Binance spot error for {symbol}: {e.message}")
            except Exception as e:
                logger.warning(f"Error fetching spot trades for {symbol}: {e}")

    except BinanceAPIException as e:
        logger.error(f"Binance account error (conn {connection['id']}): {e.message}")
    except Exception as e:
        logger.error(f"Binance spot fetch error (conn {connection['id']}): {e}")

    logger.info(f"Binance spot: fetched {len(entries)} trades for user {user_id}")
    return entries


def _fetch_futures_trades(client: BinanceClient, user_id: int, connection: dict) -> list[dict]:
    """Fetch USDM futures trades from Binance."""
    entries = []

    try:
        # Get futures trade history
        trades = client.futures_account_trades(limit=500)

        for trade in trades:
            direction = "long" if trade["side"] == "BUY" else "short"
            # For closing trades, direction is opposite
            if not trade.get("buyer", True):
                direction = "short" if direction == "long" else "long"

            price = float(trade["price"])
            qty = float(trade["qty"])
            pnl = float(trade.get("realizedPnl", 0))
            fee = float(trade.get("commission", 0))
            timestamp = datetime.fromtimestamp(
                trade["time"] / 1000, tz=timezone.utc
            ).isoformat()

            entries.append({
                "user_id": user_id,
                "external_id": f"binance_futures_{trade['id']}",
                "symbol": trade["symbol"],
                "market": "crypto_futures",
                "direction": direction,
                "entry_price": price,
                "exit_price": price,
                "quantity": qty,
                "pnl": pnl,
                "fee": abs(fee),
                "opened_at": timestamp,
                "closed_at": timestamp,
                "duration_seconds": 0,
                "status": "closed",
                "source": "binance_futures",
            })

    except BinanceAPIException as e:
        # Futures may not be enabled for all accounts
        if e.code == -2015:
            logger.info(f"Binance futures not enabled for user {user_id}")
        else:
            logger.error(f"Binance futures error (conn {connection['id']}): {e.message}")
    except Exception as e:
        logger.error(f"Binance futures fetch error (conn {connection['id']}): {e}")

    logger.info(f"Binance futures: fetched {len(entries)} trades for user {user_id}")
    return entries
