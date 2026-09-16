# Bookstore Management - GitHub Pages Static Demo

This folder is a static conversion of the Laravel Bookstore Management frontend.

- Open `index.html` through any static server.
- GitHub Pages routing uses hash URLs like `#/books` and `#/admin`.
- Demo data is seeded into `localStorage` from `assets/data/seed.json`.
- Demo password for all seeded users is `password`.
- Admin login: `admin@bookstore.test`
- Customer login: `customer@bookstore.test`

The included `.github/workflows/deploy.yml` deploys this folder when it is used as the repository root. If this folder remains inside the Laravel repository, place an equivalent workflow at the repository root and set the uploaded artifact path to `github-pages`.
