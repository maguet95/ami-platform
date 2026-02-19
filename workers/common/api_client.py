"""
Laravel API Client for AMI workers.
Communicates with the internal journal API.
"""

import os
import logging
import requests

logger = logging.getLogger(__name__)

BATCH_SIZE = 50


class LaravelApiClient:
    def __init__(self):
        self.base_url = os.environ.get("LARAVEL_API_URL", "").rstrip("/")
        self.api_key = os.environ.get("LARAVEL_API_KEY", "")

        if not self.base_url or not self.api_key:
            raise ValueError(
                "LARAVEL_API_URL and LARAVEL_API_KEY environment variables are required"
            )

        self.session = requests.Session()
        self.session.headers.update(
            {
                "X-API-Key": self.api_key,
                "Accept": "application/json",
                "Content-Type": "application/json",
            }
        )
        self.session.timeout = 30

    def health_check(self) -> dict:
        """Check API health."""
        resp = self.session.get(f"{self.base_url}/journal/health")
        resp.raise_for_status()
        return resp.json()

    def get_connections(self, conn_type: str = None) -> list[dict]:
        """Get active broker connections, optionally filtered by type."""
        params = {}
        if conn_type:
            params["type"] = conn_type
        resp = self.session.get(
            f"{self.base_url}/journal/connections", params=params
        )
        resp.raise_for_status()
        return resp.json().get("connections", [])

    def post_entries_batched(self, entries: list[dict]) -> dict:
        """Post trade entries in batches. Returns aggregate result."""
        total_created = 0
        total_duplicates = 0
        total_errors = []

        for i in range(0, len(entries), BATCH_SIZE):
            batch = entries[i : i + BATCH_SIZE]
            resp = self.session.post(
                f"{self.base_url}/journal/entries",
                json={"entries": batch},
            )
            resp.raise_for_status()
            data = resp.json()
            total_created += data.get("created", 0)
            total_duplicates += data.get("duplicates_skipped", 0)
            total_errors.extend(data.get("errors", []))

        return {
            "created": total_created,
            "duplicates_skipped": total_duplicates,
            "errors": total_errors,
        }

    def update_sync_status(
        self, connection_id: int, success: bool, error: str = None, trades_imported: int = 0
    ) -> None:
        """Report sync result for a broker connection."""
        payload = {
            "success": success,
            "trades_imported": trades_imported,
        }
        if error:
            payload["error"] = error

        resp = self.session.patch(
            f"{self.base_url}/journal/connections/{connection_id}/sync-status",
            json=payload,
        )
        resp.raise_for_status()

    def post_summaries(self, summaries: list[dict]) -> dict:
        """Post pre-calculated summaries."""
        resp = self.session.post(
            f"{self.base_url}/journal/summaries",
            json={"summaries": summaries},
        )
        resp.raise_for_status()
        return resp.json()

    def get_users_with_trades(self) -> list[int]:
        """Get user IDs that have trade entries."""
        resp = self.session.get(f"{self.base_url}/journal/users-with-trades")
        resp.raise_for_status()
        return resp.json().get("user_ids", [])

    def calculate_stats(self) -> dict:
        """Trigger server-side stats calculation."""
        resp = self.session.post(f"{self.base_url}/journal/calculate-stats")
        resp.raise_for_status()
        return resp.json()
