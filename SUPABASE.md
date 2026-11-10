# Supabase PostgreSQL setup

The PHP application connects to Supabase PostgreSQL through PDO. It does not use
the Supabase REST API keys for database access.

1. In Supabase, open **SQL Editor** and run [`supabase/schema.sql`](supabase/schema.sql).
2. If migrating an existing MySQL store, migrate its data into these PostgreSQL
   tables after creating the schema. The schema file intentionally does not copy
   the sample customer, password, or order data from the old MySQL dump. The
   old MySQL dump is not valid PostgreSQL SQL.
3. In the Supabase project, open **Connect** and copy a PostgreSQL connection
   string. Use the session pooler connection if the direct database host is not
   reachable from Vercel. Keep SSL enabled.
4. In Vercel, add that value as the server-only environment variable
   `SUPABASE_DB_URL` for the Production environment, then redeploy. Do not name
   it with a `NEXT_PUBLIC_` prefix.

The connection string may be supplied as `SUPABASE_DB_URL`, `DATABASE_URL`, or
`POSTGRES_URL`. Alternatively, set `DB_HOST`, `DB_PORT` (default `5432`),
`DB_NAME`, `DB_USER`, and `DB_PASSWORD`; `DB_SSLMODE` defaults to `require`.

The local PHP installation must have the `pdo_pgsql` extension enabled. The
Vercel PHP runtime used by this project includes PostgreSQL support.
