"""
Stats Analyzer — Thin wrapper that triggers server-side stats calculation.

Usage:
    python -m workers.stats_analyzer.main

Environment variables:
    LARAVEL_API_URL — Base URL for internal API
    LARAVEL_API_KEY — Journal API key
"""

import logging
import sys

from workers.common.api_client import LaravelApiClient

logging.basicConfig(
    level=logging.INFO,
    format="%(asctime)s [%(levelname)s] %(name)s: %(message)s",
)
logger = logging.getLogger("stats_analyzer")


def main():
    logger.info("=== Stats Analyzer starting ===")

    try:
        client = LaravelApiClient()
    except ValueError as e:
        logger.error(f"Configuration error: {e}")
        sys.exit(1)

    # Health check
    try:
        health = client.health_check()
        if health.get("status") != "ok":
            logger.error(f"API health check failed: {health}")
            sys.exit(1)
        logger.info("API health check: OK")
    except Exception as e:
        logger.error(f"API health check failed: {e}")
        sys.exit(1)

    # Trigger stats calculation on the server
    try:
        result = client.calculate_stats()
        logger.info(f"Stats calculation result: {result}")
    except Exception as e:
        logger.error(f"Stats calculation failed: {e}")
        sys.exit(1)

    logger.info("=== Stats Analyzer finished ===")


if __name__ == "__main__":
    main()
