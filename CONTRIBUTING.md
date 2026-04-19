# Contributing

Thanks for considering a contribution. This guide keeps the bar consistent across PRs.

## Reporting issues

Open an issue at https://github.com/RedberryProducts/laravel-cloud-sdk/issues. Include the package version, Laravel version, PHP version, and a minimal reproduction.

## Pull requests

- Keep each PR focused on a single concern.
- Add tests for any behavior change.
- Run the full test, lint, and static-analysis suites locally before pushing.
- Use short, imperative commit subjects (e.g. `fix: handle empty domain list`). No `Co-Authored-By` trailers, no long bulleted bodies.

## Running the test suite

```bash
composer install
vendor/bin/pest
```

Tests replay recorded Saloon fixtures from `tests/Fixtures/Saloon/` — no API token required.

## Recording new Saloon fixtures

Only needed when adding or changing a request class that hits a new endpoint.

1. Copy `.env.example` to `.env` and set a real `LARAVEL_CLOUD_TOKEN`.
2. If you are re-recording an existing fixture, delete the old file first — Saloon never overwrites an existing fixture.
3. Record via a unit test at `tests/Unit/Integrations/<Provider>/Requests/<RequestName>Test.php`. Never hand-edit fixture JSON — that bypasses the redaction pipeline in `LaravelCloudFixture` (sensitive headers, tokens, etc.).
4. Keep all `Saloon::fake([...])` entries in a single block at the top of the test.

## Code style

```bash
vendor/bin/pint
```

Pint runs in CI with `--test`; non-conforming code fails the build.

## Static analysis

```bash
vendor/bin/phpstan analyse --memory-limit=512M
```

PHPStan must pass at the configured level before merge.
