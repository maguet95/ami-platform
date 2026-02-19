"""
MetaApi fetcher stub.
Will connect to MetaTrader accounts via MetaApi cloud service.
Activate when METAAPI_TOKEN is configured.
"""

import os
import logging

logger = logging.getLogger(__name__)


def is_available() -> bool:
    """Check if MetaApi token is configured."""
    return bool(os.environ.get("METAAPI_TOKEN", ""))


def fetch_metatrader_trades(connection: dict) -> list[dict]:
    """
    Fetch trades from MetaTrader via MetaApi.
    Currently a stub — returns empty list with a log message.
    """
    if not is_available():
        logger.info(
            f"MetaApi: skipping connection {connection['id']} — "
            "METAAPI_TOKEN not configured"
        )
        return []

    # TODO: Implement MetaApi integration
    # 1. pip install metaapi-cloud-sdk
    # 2. Connect using investor password from connection credentials
    # 3. Fetch deal history
    # 4. Map to Laravel API format
    logger.info(
        f"MetaApi: token found but integration not yet implemented "
        f"(connection {connection['id']})"
    )
    return []
