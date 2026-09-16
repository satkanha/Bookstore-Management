(() => {
    const DATA_KEY = 'bookstore.pages.data.v1';
    const CART_KEY = 'bookstore.pages.cart.v1';
    const USER_KEY = 'bookstore.pages.user.v1';
    const app = document.getElementById('app');

    let seed = null;
    let data = null;
    let cart = [];
    let quickOrderBookId = null;

    const currency = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' });
    const money = (value) => currency.format(Number(value || 0));
    const today = () => new Date().toISOString().slice(0, 19).replace('T', ' ');
    const clone = (value) => JSON.parse(JSON.stringify(value));
    const esc = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;',
    }[char]));
    const slugify = (value) => String(value || 'item')
        .toLowerCase()
        .normalize('NFKD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '') || `item-${Date.now()}`;

    const saveData = () => localStorage.setItem(DATA_KEY, JSON.stringify(data));
    const saveCart = () => localStorage.setItem(CART_KEY, JSON.stringify(cart));
    const resetDemo = () => {
        data = clone(seed);
        cart = [];
        localStorage.setItem(DATA_KEY, JSON.stringify(data));
        localStorage.setItem(CART_KEY, JSON.stringify(cart));
        localStorage.removeItem(USER_KEY);
    };

    const userId = () => Number(localStorage.getItem(USER_KEY) || 0);
    const currentUser = () => data?.users.find((user) => user.id === userId()) || null;
    const isAdmin = () => currentUser()?.role === 'admin';
    const isCustomer = () => currentUser()?.role === 'customer';
    const categoryById = (id) => data.categories.find((category) => Number(category.id) === Number(id));
    const authorById = (id) => data.authors.find((author) => Number(author.id) === Number(id));
    const bookById = (id) => data.books.find((book) => Number(book.id) === Number(id));
    const bookBySlug = (slug) => data.books.find((book) => book.slug === slug);
    const activeBooks = () => data.books.filter((book) => book.status && !book.deleted_at);
    const customerOrders = (id = userId()) => data.orders.filter((order) => Number(order.user_id) === Number(id));
    const cartQuantity = () => cart.reduce((sum, item) => sum + Number(item.quantity || 0), 0);
    const cartItems = () => cart
        .map((item) => ({ ...item, book: bookById(item.bookId) }))
        .filter((item) => item.book);
    const cartSubtotal = () => cartItems().reduce((sum, item) => sum + Number(item.book.price) * Number(item.quantity), 0);
    const shippingFor = (subtotal) => subtotal >= 50 ? 0 : 4.99;

    const statusBadge = (status) => {
        const label = typeof status === 'boolean' ? (status ? 'active' : 'inactive') : String(status || 'pending');
        const map = {
            active: 'success',
            inactive: 'secondary',
            paid: 'success',
            completed: 'success',
            shipped: 'info',
            processing: 'primary',
            pending: 'warning',
            failed: 'danger',
            cancelled: 'secondary',
            deleted: 'dark',
            customer: 'primary',
            admin: 'warning',
        };
        return `<span class="badge text-bg-${map[label] || 'secondary'} text-capitalize">${esc(label.replace(/_/g, ' '))}</span>`;
    };

    const route = () => {
        const hash = window.location.hash || '#/';
        const clean = hash.replace(/^#\/?/, '');
        const [path = '', query = ''] = clean.split('?');
        return {
            path,
            segments: path.split('/').filter(Boolean),
            query: new URLSearchParams(query),
        };
    };

    const go = (path) => {
        window.location.hash = path.startsWith('#') ? path : `#/${path.replace(/^\/+/, '')}`;
    };

    const toast = (message, variant = 'success') => {
        const host = document.getElementById('toastArea');
        if (!host || !window.bootstrap) {
            return;
        }

        const wrapper = document.createElement('div');
        wrapper.className = `toast bookstore-toast text-bg-${variant} border-0 mb-2`;
        wrapper.setAttribute('role', 'status');
        wrapper.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${esc(message)}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        host.appendChild(wrapper);
        const instance = window.bootstrap.Toast.getOrCreateInstance(wrapper, { delay: 2600 });
        wrapper.addEventListener('hidden.bs.toast', () => wrapper.remove());
        instance.show();
    };

    const nav = (active = '') => {
        const user = currentUser();
        const authLinks = user ? `
            ${user.role === 'admin'
                ? `<li class="nav-item"><a class="nav-link ${active === 'admin' ? 'active' : ''}" href="#/admin">Admin</a></li>`
                : `<li class="nav-item"><a class="nav-link position-relative ${active === 'cart' ? 'active' : ''}" href="#/cart"><i class="bi bi-bag"></i> Cart ${cartQuantity() > 0 ? `<span class="badge rounded-pill text-bg-warning">${cartQuantity()}</span>` : ''}</a></li>
                   <li class="nav-item"><a class="nav-link ${active === 'orders' ? 'active' : ''}" href="#/orders">Orders</a></li>`}
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">${esc(user.name)}</a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#/profile">Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><button class="dropdown-item" type="button" data-action="logout">Logout</button></li>
                </ul>
            </li>
        ` : `
            <li class="nav-item"><a class="nav-link ${active === 'login' ? 'active' : ''}" href="#/login">Login</a></li>
            <li class="nav-item"><a class="btn btn-sm btn-warning ms-lg-2" href="#/register">Register</a></li>
        `;

        return `
            <nav class="navbar navbar-expand-lg navbar-dark navbar-bookstore sticky-top">
                <div class="container">
                    <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="#/">
                        <span class="brand-mark"><i class="bi bi-book"></i></span>
                        Bookstore
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="mainNav">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item"><a class="nav-link ${active === 'home' ? 'active' : ''}" href="#/">Home</a></li>
                            <li class="nav-item"><a class="nav-link ${active === 'books' ? 'active' : ''}" href="#/books">Books</a></li>
                        </ul>
                        <form class="nav-search d-flex me-lg-3 mb-3 mb-lg-0" data-static-form="nav-search">
                            <input class="form-control" type="search" name="search" placeholder="Search books">
                            <button class="btn btn-warning" type="submit" aria-label="Search"><i class="bi bi-search"></i></button>
                        </form>
                        <ul class="navbar-nav align-items-lg-center">${authLinks}</ul>
                    </div>
                </div>
            </nav>
        `;
    };

    const footer = () => `
        <footer class="site-footer mt-auto">
            <div class="container">
                <div class="site-footer__main">
                    <div class="row g-4 g-xl-5">
                        <div class="col-md-6 col-xl-3">
                            <a class="site-footer__brand" href="#/"><span class="site-footer__brand-icon"><i class="bi bi-book"></i></span><span>Bookstore</span></a>
                            <p class="site-footer__description">Discover, manage, and purchase your favorite books easily.</p>
                            <div class="site-footer__socials">
                                <a href="https://www.facebook.com" target="_blank" rel="noopener" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                                <a href="https://telegram.org" target="_blank" rel="noopener" aria-label="Telegram"><i class="bi bi-telegram"></i></a>
                                <a href="https://www.youtube.com" target="_blank" rel="noopener" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 col-xl-3">
                            <h2>Quick Links</h2>
                            <ul class="site-footer__links">
                                <li><a href="#/"><i class="bi bi-chevron-right"></i>Home</a></li>
                                <li><a href="#/books"><i class="bi bi-chevron-right"></i>Books</a></li>
                                <li><a href="#/about"><i class="bi bi-chevron-right"></i>About Us</a></li>
                            </ul>
                        </div>
                        <div class="col-6 col-md-3 col-xl-2">
                            <h2>Support</h2>
                            <ul class="site-footer__links">
                                <li><a href="#/contact"><i class="bi bi-chevron-right"></i>Contact Us</a></li>
                                <li><a href="#/faq"><i class="bi bi-chevron-right"></i>FAQ</a></li>
                                <li><a href="#/privacy"><i class="bi bi-chevron-right"></i>Privacy Policy</a></li>
                            </ul>
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <h2>Contact</h2>
                            <ul class="site-footer__contact">
                                <li><i class="bi bi-telephone-fill"></i><a href="tel:+85512345678">+855 12 345 678</a></li>
                                <li><i class="bi bi-envelope-fill"></i><a href="mailto:bookstore@example.com">bookstore@example.com</a></li>
                                <li><i class="bi bi-geo-alt-fill"></i><span>Phnom Penh, Cambodia</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="site-footer__bottom">
                    <div>&copy; 2026 Bookstore Management. All rights reserved.</div>
                    <div class="site-footer__legal">
                        <a href="#/privacy">Privacy Policy</a><span aria-hidden="true">&middot;</span><a href="#/terms">Terms of Use</a>
                    </div>
                </div>
            </div>
        </footer>
    `;

    const orderModal = () => `
        <div class="modal fade bookstore-modal order-modal" id="bookOrderModal" tabindex="-1" aria-labelledby="bookOrderModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <div>
                            <p class="text-amber fw-semibold mb-1">Quick order</p>
                            <h2 class="modal-title h4" id="bookOrderModalLabel">Order this book</h2>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-4">
                            <div class="col-md-5">
                                <div class="order-book-preview p-3 h-100">
                                    <img class="book-cover w-100 mb-3" data-order-cover src="assets/images/book-placeholder.svg" alt="Book cover">
                                    <h3 class="h5 mb-1" data-order-title>Book</h3>
                                    <p class="text-muted small mb-2" data-order-meta></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong class="h5 mb-0 text-amber" data-order-price>$0.00</strong>
                                        <span class="badge text-bg-success" data-order-stock></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <form data-static-form="quick-order">
                                    <div class="row g-3">
                                        <div class="col-sm-5">
                                            <label class="form-label" for="order_quantity">Quantity</label>
                                            <input id="order_quantity" class="form-control" type="number" name="quantity" value="1" min="1" required data-order-quantity>
                                        </div>
                                        <div class="col-sm-7">
                                            <label class="form-label" for="order_customer_name">Customer name</label>
                                            <input id="order_customer_name" class="form-control" name="customer_name" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label" for="order_customer_phone">Phone</label>
                                            <input id="order_customer_phone" class="form-control" name="customer_phone" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label" for="order_shipping_address">Address</label>
                                            <textarea id="order_shipping_address" class="form-control" name="shipping_address" rows="3" required></textarea>
                                        </div>
                                    </div>
                                    <div class="order-total-bar mt-4">
                                        <div><div class="text-muted small">Shipping</div><strong data-order-shipping>$0.00</strong></div>
                                        <div class="text-end"><div class="text-muted small">Total</div><strong class="h4 mb-0" data-order-total>$0.00</strong></div>
                                    </div>
                                    <div class="d-flex justify-content-end gap-2 mt-4">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle"></i> Confirm</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

    const publicLayout = (content, active = 'home', title = 'Bookstore') => {
        document.title = `${title} - Bookstore Management`;
        app.innerHTML = `
            ${nav(active)}
            <main class="py-4 flex-grow-1">${content}</main>
            ${footer()}
            ${orderModal()}
            <div id="toastArea" class="toast-container position-fixed top-0 end-0 p-3"></div>
        `;
    };

    const adminSidebar = (active) => `
        <aside class="admin-sidebar p-3">
            <a class="d-flex align-items-center gap-2 text-white text-decoration-none mb-4" href="#/admin">
                <span class="brand-mark"><i class="bi bi-book"></i></span>
                <span class="fw-bold">Bookstore Admin</span>
            </a>
            <nav class="nav flex-column gap-1">
                ${[
                    ['admin', 'Dashboard', 'bi-speedometer2'],
                    ['admin/books', 'Books', 'bi-book-half'],
                    ['admin/categories', 'Categories', 'bi-tags'],
                    ['admin/authors', 'Authors', 'bi-pen'],
                    ['admin/customers', 'Customers', 'bi-people'],
                    ['admin/orders', 'Orders', 'bi-receipt'],
                    ['admin/reports', 'Reports', 'bi-graph-up'],
                ].map(([path, label, icon]) => `<a class="nav-link ${active === path ? 'active' : ''}" href="#/${path}"><i class="bi ${icon} me-2"></i>${label}</a>`).join('')}
            </nav>
        </aside>
    `;

    const adminLayout = (content, active = 'admin', title = 'Admin') => {
        document.title = `${title} - Bookstore Management`;
        app.innerHTML = `
            <div class="admin-shell d-lg-flex">
                ${adminSidebar(active)}
                <div class="admin-content flex-grow-1 d-flex flex-column min-vh-100">
                    <nav class="navbar bg-white border-bottom">
                        <div class="container-fluid">
                            <span class="navbar-brand mb-0 h1">${esc(title)}</span>
                            <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
                                <button class="btn btn-sm btn-outline-warning" type="button" data-action="reset-demo"><i class="bi bi-arrow-counterclockwise"></i> Reset demo</button>
                                <a href="#/" class="btn btn-sm btn-outline-secondary"><i class="bi bi-shop"></i> Store</a>
                                <button class="btn btn-sm btn-outline-danger" type="button" data-action="logout"><i class="bi bi-box-arrow-right"></i> Logout</button>
                            </div>
                        </div>
                    </nav>
                    <main class="container-fluid py-4 flex-grow-1">${content}</main>
                    ${footer()}
                </div>
            </div>
            <div id="toastArea" class="toast-container position-fixed top-0 end-0 p-3"></div>
        `;
    };

    const emptyState = (title, message = '') => `
        <div class="empty-state p-4 text-center">
            <div class="stat-icon mx-auto mb-3"><i class="bi bi-inbox"></i></div>
            <h2 class="h5">${esc(title)}</h2>
            ${message ? `<p class="text-muted mb-0">${esc(message)}</p>` : ''}
        </div>
    `;

    const bookCard = (book, controls = false) => {
        const author = authorById(book.author_id);
        const category = categoryById(book.category_id);
        return `
            <div class="book-card h-100 p-3">
                <a href="#/books/${esc(book.slug)}"><img class="book-cover mb-3" src="${esc(book.image)}" alt="${esc(book.title)} cover"></a>
                <div class="d-flex flex-column h-100">
                    <h3 class="h6 book-title"><a class="text-decoration-none text-dark" href="#/books/${esc(book.slug)}">${esc(book.title)}</a></h3>
                    <div class="text-muted small">${esc(author?.name || 'Unknown author')}</div>
                    <div class="small text-muted mb-2">${esc(category?.name || 'Uncategorized')}</div>
                    ${controls ? `
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <strong>${money(book.price)}</strong>
                            <span class="badge ${book.stock > 0 ? 'text-bg-success' : 'text-bg-secondary'}">${book.stock > 0 ? `${book.stock} in stock` : 'Out of stock'}</span>
                        </div>
                        <div class="book-actions mt-3 d-grid gap-2">
                            <button type="button" class="btn btn-sm btn-warning" data-action="quick-order" data-book-id="${book.id}" ${book.stock < 1 ? 'disabled' : ''}>
                                <i class="bi bi-bag-check"></i> Order
                            </button>
                            ${isCustomer() ? `<button type="button" class="btn btn-sm btn-outline-primary" data-action="add-cart" data-book-id="${book.id}"><i class="bi bi-bag-plus"></i> Add to cart</button>` : ''}
                        </div>
                    ` : ''}
                </div>
            </div>
        `;
    };

    const renderHome = () => {
        const featured = activeBooks().filter((book) => book.featured).slice(0, 8);
        const latest = activeBooks().slice().sort((a, b) => b.id - a.id).slice(0, 8);
        publicLayout(`
            <section class="hero-band mb-5">
                <video class="hero-video" autoplay muted loop playsinline preload="metadata" aria-hidden="true">
                    <source src="assets/images/bookstore-background.mp4" type="video/mp4">
                </video>
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-8 col-xl-7">
                            <h1 class="hero-title"><span>Bookstore</span> <span class="hero-title__accent">Management</span></h1>
                            <p class="lead mb-4">Browse featured titles, discover new authors, and place demo orders through a static GitHub Pages checkout flow.</p>
                            <div class="d-flex flex-wrap gap-2">
                                <a class="btn btn-warning btn-lg" href="#/books"><i class="bi bi-search"></i> Browse books</a>
                                <a class="btn btn-outline-light btn-lg" href="#/login"><i class="bi bi-person-check"></i> Demo login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="container mb-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h4 mb-0">Featured Books</h2>
                    <a href="#/books" class="btn btn-sm btn-outline-primary">View all</a>
                </div>
                <div class="row g-4">${featured.map((book) => `<div class="col-6 col-md-4 col-lg-3">${bookCard(book)}</div>`).join('')}</div>
            </section>
            <section class="container">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h4 mb-0">Latest Books</h2>
                    <a href="#/books?sort=newest" class="btn btn-sm btn-outline-primary">Newest</a>
                </div>
                <div class="row g-4">${latest.map((book) => `<div class="col-6 col-md-4 col-lg-3">${bookCard(book)}</div>`).join('')}</div>
            </section>
        `, 'home', 'Bookstore');
    };

    const filteredBooks = (params) => {
        const search = (params.get('search') || '').toLowerCase();
        const category = params.get('category');
        const author = params.get('author');
        const sort = params.get('sort') || 'newest';
        let books = activeBooks().filter((book) => {
            const authorName = authorById(book.author_id)?.name || '';
            const matchesSearch = !search || [book.title, book.isbn, authorName].join(' ').toLowerCase().includes(search);
            return matchesSearch
                && (!category || String(book.category_id) === category)
                && (!author || String(book.author_id) === author);
        });

        books = books.sort((a, b) => {
            if (sort === 'title') return a.title.localeCompare(b.title);
            if (sort === 'price_low') return Number(a.price) - Number(b.price);
            if (sort === 'price_high') return Number(b.price) - Number(a.price);
            return Number(b.id) - Number(a.id);
        });

        return books;
    };

    const renderBooks = (params) => {
        const books = filteredBooks(params);
        publicLayout(`
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-1">Books</h1>
                        <div class="text-muted">Search, filter, and sort the catalog.</div>
                    </div>
                </div>
                <form class="surface-card p-3 mb-4" data-static-form="books-filter">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-lg-4">
                            <label class="form-label" for="search">Search</label>
                            <input id="search" class="form-control" type="search" name="search" value="${esc(params.get('search') || '')}" placeholder="Title, ISBN, or author">
                        </div>
                        <div class="col-6 col-lg-2">
                            <label class="form-label" for="category">Category</label>
                            <select id="category" class="form-select" name="category">
                                <option value="">All</option>
                                ${data.categories.map((category) => `<option value="${category.id}" ${params.get('category') === String(category.id) ? 'selected' : ''}>${esc(category.name)}</option>`).join('')}
                            </select>
                        </div>
                        <div class="col-6 col-lg-2">
                            <label class="form-label" for="author">Author</label>
                            <select id="author" class="form-select" name="author">
                                <option value="">All</option>
                                ${data.authors.map((author) => `<option value="${author.id}" ${params.get('author') === String(author.id) ? 'selected' : ''}>${esc(author.name)}</option>`).join('')}
                            </select>
                        </div>
                        <div class="col-6 col-lg-2">
                            <label class="form-label" for="sort">Sort</label>
                            <select id="sort" class="form-select" name="sort">
                                ${[
                                    ['newest', 'Newest'],
                                    ['title', 'Title'],
                                    ['price_low', 'Price low'],
                                    ['price_high', 'Price high'],
                                ].map(([value, label]) => `<option value="${value}" ${(params.get('sort') || 'newest') === value ? 'selected' : ''}>${label}</option>`).join('')}
                            </select>
                        </div>
                        <div class="col-6 col-lg-2">
                            <button class="btn btn-primary w-100" type="submit"><i class="bi bi-search"></i> Search</button>
                        </div>
                    </div>
                </form>
                ${books.length ? `<div class="row g-4">${books.map((book) => `<div class="col-6 col-md-4 col-lg-3">${bookCard(book, true)}</div>`).join('')}</div>` : emptyState('No books found', 'Try a broader search or remove a filter.')}
            </div>
        `, 'books', 'Books');
    };

    const renderBookDetail = (slug) => {
        const book = bookBySlug(slug);
        if (!book) {
            publicLayout(`<div class="container">${emptyState('Book not found', 'Return to the catalog and choose another title.')}</div>`, 'books', 'Book not found');
            return;
        }

        const author = authorById(book.author_id);
        const category = categoryById(book.category_id);
        const related = activeBooks().filter((item) => item.id !== book.id && item.category_id === book.category_id).slice(0, 4);
        publicLayout(`
            <div class="container">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#/">Home</a></li>
                        <li class="breadcrumb-item"><a href="#/books">Books</a></li>
                        <li class="breadcrumb-item active" aria-current="page">${esc(book.title)}</li>
                    </ol>
                </nav>
                <div class="surface-card p-4 mb-5">
                    <div class="row g-4">
                        <div class="col-md-4 col-lg-3"><img class="book-cover w-100" src="${esc(book.image)}" alt="${esc(book.title)} cover"></div>
                        <div class="col-md-8 col-lg-9">
                            <div class="d-flex flex-wrap gap-2 mb-2">
                                <span class="badge text-bg-light">${esc(category?.name || 'Uncategorized')}</span>
                                ${book.featured ? '<span class="badge text-bg-warning">Featured</span>' : ''}
                            </div>
                            <h1 class="h2">${esc(book.title)}</h1>
                            <p class="text-muted mb-2">by ${esc(author?.name || 'Unknown author')}</p>
                            <div class="h3 text-amber mb-3">${money(book.price)}</div>
                            <dl class="row">
                                <dt class="col-sm-3">ISBN</dt><dd class="col-sm-9">${esc(book.isbn)}</dd>
                                <dt class="col-sm-3">Stock</dt><dd class="col-sm-9">${book.stock} available</dd>
                                <dt class="col-sm-3">Published</dt><dd class="col-sm-9">${esc(book.publication_date || 'Not listed')}</dd>
                            </dl>
                            <p>${esc(book.description)}</p>
                            <div class="d-flex flex-wrap align-items-end gap-2">
                                ${isCustomer() ? `
                                    <form class="d-flex flex-wrap align-items-end gap-2" data-static-form="add-cart">
                                        <input type="hidden" name="book_id" value="${book.id}">
                                        <div>
                                            <label class="form-label" for="quantity">Quantity</label>
                                            <input id="quantity" class="form-control" type="number" name="quantity" value="1" min="1" max="${Math.max(book.stock, 1)}" ${book.stock < 1 ? 'disabled' : ''}>
                                        </div>
                                        <button class="btn btn-primary" type="submit" ${book.stock < 1 ? 'disabled' : ''}><i class="bi bi-bag-plus"></i> Add to cart</button>
                                    </form>
                                ` : `<a class="btn btn-primary" href="#/login"><i class="bi bi-person"></i> Login to add to cart</a>`}
                                <button type="button" class="btn btn-warning" data-action="quick-order" data-book-id="${book.id}" ${book.stock < 1 ? 'disabled' : ''}><i class="bi bi-bag-check"></i> Order</button>
                            </div>
                        </div>
                    </div>
                </div>
                <h2 class="h4 mb-3">Related Books</h2>
                <div class="row g-4">${related.map((item) => `<div class="col-6 col-md-3">${bookCard(item, true)}</div>`).join('') || `<div class="col-12">${emptyState('No related books', 'Check back after more catalog titles are added.')}</div>`}</div>
            </div>
        `, 'books', book.title);
    };

    const renderLogin = () => {
        publicLayout(`
            <div class="container py-5 flex-grow-1">
                <div class="row justify-content-center">
                    <div class="col-sm-10 col-md-7 col-lg-5">
                        <div class="text-center mb-3">
                            <a class="d-inline-flex align-items-center gap-2 text-decoration-none" href="#/">
                                <span class="brand-mark"><i class="bi bi-book"></i></span>
                                <span class="h4 mb-0 fw-bold text-dark">Bookstore</span>
                            </a>
                        </div>
                        <div class="surface-card p-4">
                            <h1 class="h4 mb-3">Login</h1>
                            <div class="demo-credential p-3 mb-3 small">
                                <strong>Demo:</strong> admin@bookstore.test / password<br>
                                <strong>Customer:</strong> customer@bookstore.test / password
                            </div>
                            <form data-static-form="login">
                                <div class="mb-3">
                                    <label class="form-label" for="email">Email</label>
                                    <input id="email" class="form-control" type="email" name="email" value="customer@bookstore.test" required autofocus>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="password">Password</label>
                                    <input id="password" class="form-control" type="password" name="password" value="password" required>
                                </div>
                                <button class="btn btn-primary w-100" type="submit">Login</button>
                                <p class="text-center mt-3 mb-0">No account? <a href="#/register">Register</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        `, 'login', 'Login');
    };

    const renderRegister = () => {
        publicLayout(`
            <div class="container py-5 flex-grow-1">
                <div class="row justify-content-center">
                    <div class="col-sm-10 col-md-7 col-lg-5">
                        <div class="surface-card p-4">
                            <h1 class="h4 mb-3">Register</h1>
                            <form data-static-form="register">
                                <div class="mb-3"><label class="form-label" for="name">Name</label><input id="name" class="form-control" name="name" required></div>
                                <div class="mb-3"><label class="form-label" for="email">Email</label><input id="email" class="form-control" type="email" name="email" required></div>
                                <div class="mb-3"><label class="form-label" for="password">Password</label><input id="password" class="form-control" type="password" name="password" value="password" required></div>
                                <button class="btn btn-primary w-100" type="submit">Create account</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        `, 'login', 'Register');
    };

    const renderProfile = () => {
        const user = currentUser();
        if (!user) {
            go('/login');
            return;
        }

        publicLayout(`
            <div class="container">
                <h1 class="h3 mb-4">Profile</h1>
                <div class="surface-card p-4">
                    <form data-static-form="profile">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label" for="name">Name</label><input id="name" class="form-control" name="name" value="${esc(user.name)}" required></div>
                            <div class="col-md-6"><label class="form-label" for="email">Email</label><input id="email" class="form-control" type="email" name="email" value="${esc(user.email)}" required></div>
                            <div class="col-md-6"><label class="form-label" for="phone">Phone</label><input id="phone" class="form-control" name="phone" value="${esc(user.phone || '')}"></div>
                            <div class="col-12"><label class="form-label" for="address">Address</label><textarea id="address" class="form-control" name="address" rows="3">${esc(user.address || '')}</textarea></div>
                        </div>
                        <button class="btn btn-primary mt-4" type="submit">Save profile</button>
                    </form>
                </div>
            </div>
        `, '', 'Profile');
    };

    const renderCart = () => {
        if (!isCustomer()) {
            go('/login');
            return;
        }
        const items = cartItems();
        const subtotal = cartSubtotal();
        publicLayout(`
            <div class="container">
                <h1 class="h3 mb-4">Shopping Cart</h1>
                ${items.length ? `
                    <div class="surface-card p-3">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead><tr><th>Book</th><th>Price</th><th style="width: 160px;">Quantity</th><th>Subtotal</th><th></th></tr></thead>
                                <tbody>
                                    ${items.map((item) => `
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <img src="${esc(item.book.image)}" class="book-cover book-cover--sm" alt="${esc(item.book.title)} cover">
                                                    <div><a class="fw-semibold text-decoration-none" href="#/books/${esc(item.book.slug)}">${esc(item.book.title)}</a><div class="text-muted small">${esc(authorById(item.book.author_id)?.name || '')}</div></div>
                                                </div>
                                            </td>
                                            <td>${money(item.book.price)}</td>
                                            <td>
                                                <input class="form-control form-control-sm" type="number" min="1" max="${item.book.stock}" value="${item.quantity}" data-action="cart-qty" data-book-id="${item.book.id}">
                                            </td>
                                            <td>${money(item.book.price * item.quantity)}</td>
                                            <td class="text-end"><button class="btn btn-sm btn-outline-danger" type="button" data-action="remove-cart" data-book-id="${item.book.id}"><i class="bi bi-trash"></i></button></td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                            <button class="btn btn-outline-secondary" type="button" data-action="clear-cart">Clear cart</button>
                            <div class="text-md-end">
                                <div class="h4">Subtotal: ${money(subtotal)}</div>
                                <a class="btn btn-primary" href="#/checkout"><i class="bi bi-credit-card"></i> Checkout</a>
                            </div>
                        </div>
                    </div>
                ` : emptyState('Your cart is empty', 'Find a book you like and add it to your cart.')}
            </div>
        `, 'cart', 'Cart');
    };

    const renderCheckout = () => {
        if (!isCustomer()) {
            go('/login');
            return;
        }

        const user = currentUser();
        const items = cartItems();
        if (!items.length) {
            go('/cart');
            return;
        }
        const subtotal = cartSubtotal();
        const shipping = shippingFor(subtotal);
        publicLayout(`
            <div class="container">
                <h1 class="h3 mb-4">Checkout</h1>
                <div class="row g-4">
                    <div class="col-lg-7">
                        <form class="surface-card p-4" data-static-form="checkout">
                            <div class="row g-3">
                                <div class="col-md-6"><label class="form-label" for="customer_name">Name</label><input id="customer_name" class="form-control" name="customer_name" value="${esc(user.name)}" required></div>
                                <div class="col-md-6"><label class="form-label" for="customer_email">Email</label><input id="customer_email" class="form-control" type="email" name="customer_email" value="${esc(user.email)}" required></div>
                                <div class="col-md-6"><label class="form-label" for="customer_phone">Phone</label><input id="customer_phone" class="form-control" name="customer_phone" value="${esc(user.phone || '')}" required></div>
                                <div class="col-md-6">
                                    <label class="form-label" for="payment_method">Payment method</label>
                                    <select id="payment_method" class="form-select" name="payment_method" required>
                                        <option value="cash_on_delivery">Cash on Delivery</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                    </select>
                                </div>
                                <div class="col-12"><label class="form-label" for="shipping_address">Shipping address</label><textarea id="shipping_address" class="form-control" name="shipping_address" rows="3" required>${esc(user.address || '')}</textarea></div>
                                <div class="col-12"><label class="form-label" for="notes">Notes</label><textarea id="notes" class="form-control" name="notes" rows="2"></textarea></div>
                            </div>
                            <button class="btn btn-primary mt-4" type="submit"><i class="bi bi-check2-circle"></i> Place order</button>
                        </form>
                    </div>
                    <div class="col-lg-5">
                        <div class="surface-card p-4">
                            <h2 class="h5">Order Summary</h2>
                            ${items.map((item) => `<div class="d-flex justify-content-between py-2 border-bottom"><div>${esc(item.book.title)} <span class="text-muted">x${item.quantity}</span></div><div>${money(item.book.price * item.quantity)}</div></div>`).join('')}
                            <div class="d-flex justify-content-between mt-3"><span>Subtotal</span><strong>${money(subtotal)}</strong></div>
                            <div class="d-flex justify-content-between"><span>Shipping</span><strong>${money(shipping)}</strong></div>
                            <div class="d-flex justify-content-between h5 mt-3"><span>Total</span><span>${money(subtotal + shipping)}</span></div>
                        </div>
                    </div>
                </div>
            </div>
        `, 'cart', 'Checkout');
    };

    const renderOrders = () => {
        if (!isCustomer()) {
            go('/login');
            return;
        }
        const orders = customerOrders().sort((a, b) => new Date(b.ordered_at) - new Date(a.ordered_at));
        publicLayout(`
            <div class="container">
                <h1 class="h3 mb-4">My Orders</h1>
                ${orders.length ? `
                    <div class="surface-card p-3 table-responsive">
                        <table class="table">
                            <thead><tr><th>Order</th><th>Date</th><th>Items</th><th>Total</th><th>Status</th><th></th></tr></thead>
                            <tbody>
                                ${orders.map((order) => `<tr><td>${esc(order.order_number)}</td><td>${esc(order.ordered_at.slice(0, 10))}</td><td>${order.items.length}</td><td>${money(order.total_amount)}</td><td>${statusBadge(order.order_status)}</td><td class="text-end"><a class="btn btn-sm btn-outline-primary" href="#/orders/${order.id}">Details</a></td></tr>`).join('')}
                            </tbody>
                        </table>
                    </div>
                ` : emptyState('No orders yet', 'Your completed checkouts will appear here.')}
            </div>
        `, 'orders', 'My Orders');
    };

    const renderOrderDetail = (id, admin = false) => {
        const order = data.orders.find((item) => Number(item.id) === Number(id));
        if (!order || (!admin && Number(order.user_id) !== userId())) {
            publicLayout(`<div class="container">${emptyState('Order not found')}</div>`, 'orders', 'Order not found');
            return;
        }
        const content = `
            <div class="${admin ? 'container-fluid' : 'container'}">
                <div class="surface-card p-4">
                    <div class="d-flex flex-wrap justify-content-between gap-3 mb-4">
                        <div>
                            <div class="text-muted small">Order</div>
                            <h1 class="h3 mb-0">${esc(order.order_number)}</h1>
                        </div>
                        <div class="d-flex gap-2">${statusBadge(order.order_status)} ${statusBadge(order.payment_status)}</div>
                    </div>
                    <div class="row g-4">
                        <div class="col-lg-7">
                            <h2 class="h5">Items</h2>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead><tr><th>Book</th><th>Qty</th><th>Unit</th><th>Subtotal</th></tr></thead>
                                    <tbody>${order.items.map((item) => `<tr><td>${esc(item.book_title)}</td><td>${item.quantity}</td><td>${money(item.unit_price)}</td><td>${money(item.subtotal)}</td></tr>`).join('')}</tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <h2 class="h5">Customer</h2>
                            <p class="mb-1"><strong>${esc(order.customer_name)}</strong></p>
                            <p class="text-muted mb-1">${esc(order.customer_email)}</p>
                            <p class="text-muted">${esc(order.customer_phone)}</p>
                            <p>${esc(order.shipping_address)}</p>
                            <div class="d-flex justify-content-between"><span>Subtotal</span><strong>${money(order.subtotal)}</strong></div>
                            <div class="d-flex justify-content-between"><span>Shipping</span><strong>${money(order.shipping_fee)}</strong></div>
                            <div class="d-flex justify-content-between h5 mt-2"><span>Total</span><span>${money(order.total_amount)}</span></div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        if (admin) {
            adminLayout(content, 'admin/orders', order.order_number);
        } else {
            publicLayout(content, 'orders', order.order_number);
        }
    };

    const pageContent = (type) => {
        const map = {
            about: ['About Us', 'Built for readers', 'This static version preserves the bookstore browsing, ordering, and admin demo experience without a Laravel server.', ['Catalog workflows', 'Local demo data', 'GitHub Pages ready']],
            contact: ['Contact Us', 'We are here to help', 'Use the demo links below to explore support touchpoints in the static site.', ['Phone support', 'Email help', 'Phnom Penh location']],
            faq: ['FAQ', 'Common questions', 'The GitHub Pages build runs fully in your browser using localStorage.', ['No backend required', 'Reset demo anytime', 'Hash routes for Pages']],
            privacy: ['Privacy Policy', 'Demo-only storage', 'This static demo stores seeded data and your test actions in your browser localStorage.', ['Local only', 'Resettable', 'No server database']],
            terms: ['Terms of Use', 'Static demo terms', 'Use this build for local and GitHub Pages demonstrations of the bookstore UI.', ['Demo content', 'No real payments', 'Educational use']],
        };
        const [title, eyebrow, intro, items] = map[type] || map.about;
        publicLayout(`
            <section class="container py-4 py-lg-5">
                <div class="row justify-content-center">
                    <div class="col-lg-9">
                        <div class="surface-card p-4 p-lg-5">
                            <div class="text-amber fw-semibold mb-2">${esc(eyebrow)}</div>
                            <h1 class="h2 fw-bold mb-3">${esc(title)}</h1>
                            <p class="lead text-muted mb-4">${esc(intro)}</p>
                            <div class="row g-3">
                                ${items.map((item, index) => `
                                    <div class="col-md-4">
                                        <div class="footer-page-card h-100">
                                            <div class="footer-page-card__icon"><i class="bi ${['bi-shop', 'bi-database-check', 'bi-github'][index]}"></i></div>
                                            <h2 class="h6 fw-bold mb-2">${esc(item)}</h2>
                                            <p class="text-muted small mb-0">Available in the browser-only demo experience.</p>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        `, '', title);
    };

    const adminStats = () => {
        const revenue = data.orders.filter((order) => order.payment_status === 'paid').reduce((sum, order) => sum + Number(order.total_amount), 0);
        return {
            books: data.books.length,
            customers: data.users.filter((user) => user.role === 'customer').length,
            orders: data.orders.length,
            revenue,
            lowStock: data.books.filter((book) => Number(book.stock) <= 5).length,
            pending: data.orders.filter((order) => order.order_status === 'pending').length,
        };
    };

    const monthlyRevenue = () => {
        const buckets = {};
        data.orders.filter((order) => order.payment_status === 'paid').forEach((order) => {
            const key = new Date(order.ordered_at).toLocaleString('en-US', { month: 'short', year: '2-digit' });
            buckets[key] = (buckets[key] || 0) + Number(order.total_amount);
        });
        return Object.entries(buckets).slice(-6);
    };

    const renderAdminDashboard = () => {
        const stats = adminStats();
        const chart = monthlyRevenue();
        const max = Math.max(...chart.map(([, value]) => value), 1);
        const popular = {};
        data.orders.flatMap((order) => order.items).forEach((item) => {
            popular[item.book_title] = (popular[item.book_title] || 0) + Number(item.quantity);
        });
        const popularRows = Object.entries(popular).sort((a, b) => b[1] - a[1]).slice(0, 5);
        const recentOrders = data.orders.slice().sort((a, b) => new Date(b.ordered_at) - new Date(a.ordered_at)).slice(0, 8);
        const statCards = [
            ['Books', stats.books, 'bi-book-half'],
            ['Customers', stats.customers, 'bi-people'],
            ['Orders', stats.orders, 'bi-receipt'],
            ['Revenue', money(stats.revenue), 'bi-cash-stack'],
            ['Low Stock', stats.lowStock, 'bi-exclamation-triangle'],
            ['Pending', stats.pending, 'bi-hourglass-split'],
        ];
        adminLayout(`
            <div class="row g-3 mb-4">
                ${statCards.map(([label, value, icon]) => `<div class="col-sm-6 col-xl-2"><div class="admin-card p-3 h-100"><div class="stat-icon mb-3"><i class="bi ${icon}"></i></div><div class="text-muted small">${label}</div><div class="h4 mb-0">${value}</div></div></div>`).join('')}
            </div>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="admin-card p-4">
                        <h2 class="h5">Monthly Sales</h2>
                        <div class="admin-chart">${chart.map(([label, value]) => `<div><div class="admin-chart__bar" style="height:${Math.max(18, (value / max) * 170)}px">${money(value)}</div><div class="admin-chart__label">${esc(label)}</div></div>`).join('') || '<p class="text-muted mb-0">No paid revenue yet.</p>'}</div>
                    </div>
                </div>
                <div class="col-lg-4"><div class="admin-card p-4 h-100"><h2 class="h5">Popular Books</h2>${popularRows.map(([title, sold]) => `<div class="d-flex justify-content-between border-bottom py-2"><span>${esc(title)}</span><strong>${sold}</strong></div>`).join('') || '<p class="text-muted mb-0">No sales yet.</p>'}</div></div>
                <div class="col-lg-8">
                    <div class="admin-card p-4">
                        <h2 class="h5">Recent Orders</h2>
                        <div class="table-responsive"><table class="table"><thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Status</th><th></th></tr></thead><tbody>${recentOrders.map((order) => `<tr><td>${esc(order.order_number)}</td><td>${esc(order.customer_name)}</td><td>${money(order.total_amount)}</td><td>${statusBadge(order.order_status)}</td><td><a href="#/admin/orders/${order.id}" class="btn btn-sm btn-outline-primary">Open</a></td></tr>`).join('')}</tbody></table></div>
                    </div>
                </div>
                <div class="col-lg-4"><div class="admin-card p-4"><h2 class="h5">Low Stock</h2>${data.books.filter((book) => book.stock <= 5).slice(0, 6).map((book) => `<div class="d-flex justify-content-between border-bottom py-2"><span>${esc(book.title)}</span><span class="badge text-bg-danger">${book.stock}</span></div>`).join('') || '<p class="text-muted mb-0">No low-stock books.</p>'}</div></div>
            </div>
        `, 'admin', 'Dashboard');
    };

    const renderAdminBooks = () => {
        adminLayout(`
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0">Books</h1>
            </div>
            <form class="admin-card p-3 mb-3" data-static-form="admin-book-create">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3"><label class="form-label">Title</label><input class="form-control" name="title" required></div>
                    <div class="col-md-2"><label class="form-label">Category</label><select class="form-select" name="category_id">${data.categories.map((category) => `<option value="${category.id}">${esc(category.name)}</option>`).join('')}</select></div>
                    <div class="col-md-2"><label class="form-label">Author</label><select class="form-select" name="author_id">${data.authors.map((author) => `<option value="${author.id}">${esc(author.name)}</option>`).join('')}</select></div>
                    <div class="col-md-2"><label class="form-label">Price</label><input class="form-control" type="number" step="0.01" name="price" value="19.99" required></div>
                    <div class="col-md-2"><label class="form-label">Stock</label><input class="form-control" type="number" name="stock" value="20" required></div>
                    <div class="col-md-1"><button class="btn btn-primary w-100" title="Add book"><i class="bi bi-plus-lg"></i></button></div>
                </div>
            </form>
            <div class="admin-card p-3 table-responsive">
                <table class="table">
                    <thead><tr><th>Book</th><th>Category</th><th>Author</th><th>Price</th><th>Stock</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                        ${data.books.map((book) => `
                            <tr>
                                <td><div class="d-flex align-items-center gap-2"><img class="book-cover book-cover--sm" src="${esc(book.image)}" alt=""><div><div class="fw-semibold">${esc(book.title)}</div><div class="text-muted small">${esc(book.isbn)}</div></div></div></td>
                                <td>${esc(categoryById(book.category_id)?.name || '')}</td>
                                <td>${esc(authorById(book.author_id)?.name || '')}</td>
                                <td>${money(book.price)}</td>
                                <td><input class="form-control form-control-sm" style="width: 5.5rem;" type="number" min="0" value="${book.stock}" data-action="admin-stock" data-book-id="${book.id}"></td>
                                <td>${statusBadge(book.deleted_at ? 'deleted' : book.status)}</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-secondary" data-action="admin-toggle-book" data-book-id="${book.id}">${book.status ? 'Disable' : 'Enable'}</button>
                                    <button class="btn btn-sm btn-outline-danger" data-action="admin-delete-book" data-book-id="${book.id}"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        `, 'admin/books', 'Books');
    };

    const renderAdminOrders = () => {
        adminLayout(`
            <div class="admin-card p-3 table-responsive">
                <table class="table">
                    <thead><tr><th>Order</th><th>Customer</th><th>Date</th><th>Total</th><th>Order</th><th>Payment</th><th></th></tr></thead>
                    <tbody>
                        ${data.orders.slice().sort((a, b) => new Date(b.ordered_at) - new Date(a.ordered_at)).map((order) => `
                            <tr>
                                <td>${esc(order.order_number)}</td>
                                <td>${esc(order.customer_name)}<div class="text-muted small">${esc(order.customer_email)}</div></td>
                                <td>${esc(order.ordered_at.slice(0, 10))}</td>
                                <td>${money(order.total_amount)}</td>
                                <td><select class="form-select form-select-sm" data-action="admin-order-status" data-order-id="${order.id}">${['pending', 'processing', 'shipped', 'completed', 'cancelled'].map((status) => `<option value="${status}" ${order.order_status === status ? 'selected' : ''}>${status}</option>`).join('')}</select></td>
                                <td><select class="form-select form-select-sm" data-action="admin-payment-status" data-order-id="${order.id}">${['pending', 'paid', 'failed', 'refunded'].map((status) => `<option value="${status}" ${order.payment_status === status ? 'selected' : ''}>${status}</option>`).join('')}</select></td>
                                <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="#/admin/orders/${order.id}">Open</a></td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        `, 'admin/orders', 'Orders');
    };

    const simpleAdminTable = (kind) => {
        const config = {
            categories: ['Categories', data.categories, 'name', 'description', 'admin-category-create'],
            authors: ['Authors', data.authors, 'name', 'biography', 'admin-author-create'],
            customers: ['Customers', data.users.filter((user) => user.role === 'customer'), 'name', 'email', null],
        }[kind];
        const [title, rows, primary, secondary, formName] = config;
        adminLayout(`
            ${formName ? `
                <form class="admin-card p-3 mb-3" data-static-form="${formName}">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4"><label class="form-label">Name</label><input class="form-control" name="name" required></div>
                        <div class="col-md-6"><label class="form-label">Description</label><input class="form-control" name="description"></div>
                        <div class="col-md-2"><button class="btn btn-primary w-100"><i class="bi bi-plus-lg"></i> Add</button></div>
                    </div>
                </form>
            ` : ''}
            <div class="admin-card p-3 table-responsive">
                <table class="table">
                    <thead><tr><th>${title.slice(0, -1)}</th><th>Details</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                        ${rows.map((row) => `
                            <tr>
                                <td class="fw-semibold">${esc(row[primary])}</td>
                                <td class="text-muted">${esc(row[secondary] || '')}</td>
                                <td>${statusBadge(row.is_active ?? row.status)}</td>
                                <td class="text-end">${kind === 'customers' ? `<button class="btn btn-sm btn-outline-secondary" data-action="admin-toggle-user" data-user-id="${row.id}">${row.is_active ? 'Disable' : 'Enable'}</button>` : `<button class="btn btn-sm btn-outline-secondary" data-action="admin-toggle-${kind.slice(0, -1)}" data-id="${row.id}">${row.status ? 'Disable' : 'Enable'}</button>`}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        `, `admin/${kind}`, title);
    };

    const renderReports = () => {
        const paid = data.orders.filter((order) => order.payment_status === 'paid');
        const revenue = paid.reduce((sum, order) => sum + Number(order.total_amount), 0);
        const statusCounts = data.orders.reduce((carry, order) => ({ ...carry, [order.order_status]: (carry[order.order_status] || 0) + 1 }), {});
        const sold = {};
        data.orders.flatMap((order) => order.items).forEach((item) => {
            if (!sold[item.book_title]) sold[item.book_title] = { sold: 0, revenue: 0 };
            sold[item.book_title].sold += Number(item.quantity);
            sold[item.book_title].revenue += Number(item.subtotal);
        });
        const best = Object.entries(sold).sort((a, b) => b[1].sold - a[1].sold).slice(0, 8);
        adminLayout(`
            <div class="row g-3 mb-4">
                <div class="col-md-6"><div class="admin-card p-4"><div class="text-muted">Paid revenue</div><div class="h3">${money(revenue)}</div></div></div>
                <div class="col-md-6"><div class="admin-card p-4"><div class="text-muted">Total orders</div><div class="h3">${data.orders.length}</div></div></div>
            </div>
            <div class="row g-4">
                <div class="col-lg-7"><div class="admin-card p-4"><h2 class="h5">Best-Selling Books</h2><table class="table"><thead><tr><th>Book</th><th>Sold</th><th>Revenue</th></tr></thead><tbody>${best.map(([title, item]) => `<tr><td>${esc(title)}</td><td>${item.sold}</td><td>${money(item.revenue)}</td></tr>`).join('')}</tbody></table></div></div>
                <div class="col-lg-5"><div class="admin-card p-4"><h2 class="h5">Orders by Status</h2>${Object.entries(statusCounts).map(([status, total]) => `<div class="d-flex justify-content-between border-bottom py-2"><span class="text-capitalize">${esc(status)}</span><strong>${total}</strong></div>`).join('')}</div></div>
                <div class="col-lg-12"><div class="admin-card p-4"><h2 class="h5">Low-Stock Books</h2>${data.books.filter((book) => book.stock <= 5).map((book) => `<div class="d-flex justify-content-between border-bottom py-2"><span>${esc(book.title)}</span><span class="badge text-bg-danger">${book.stock}</span></div>`).join('') || '<p class="text-muted mb-0">No low-stock books.</p>'}</div></div>
            </div>
        `, 'admin/reports', 'Reports');
    };

    const createOrder = (items, fields) => {
        const user = currentUser();
        if (!user) throw new Error('Please login before ordering.');
        items.forEach((item) => {
            const book = bookById(item.bookId);
            if (!book || !book.status) throw new Error('One book is no longer available.');
            if (Number(item.quantity) > Number(book.stock)) throw new Error(`${book.title} only has ${book.stock} copies available.`);
        });

        const subtotal = items.reduce((sum, item) => sum + Number(bookById(item.bookId).price) * Number(item.quantity), 0);
        const shipping = shippingFor(subtotal);
        const now = today();
        const id = Math.max(0, ...data.orders.map((order) => Number(order.id))) + 1;
        const order = {
            id,
            user_id: user.id,
            order_number: `ORD-${now.slice(0, 10).replace(/-/g, '')}-${String(id).padStart(6, '0')}`,
            customer_name: fields.customer_name || user.name,
            customer_email: fields.customer_email || user.email,
            customer_phone: fields.customer_phone || user.phone || '',
            shipping_address: fields.shipping_address || user.address || '',
            subtotal: Number(subtotal.toFixed(2)),
            shipping_fee: shipping,
            total_amount: Number((subtotal + shipping).toFixed(2)),
            payment_method: fields.payment_method || 'cash_on_delivery',
            payment_status: 'pending',
            order_status: 'pending',
            notes: fields.notes || null,
            ordered_at: now,
            created_at: now,
            updated_at: now,
            items: items.map((item, index) => {
                const book = bookById(item.bookId);
                const quantity = Number(item.quantity);
                const line = Number((Number(book.price) * quantity).toFixed(2));
                book.stock = Math.max(0, Number(book.stock) - quantity);
                return {
                    id: index + 1,
                    order_id: id,
                    book_id: book.id,
                    book_title: book.title,
                    book_isbn: book.isbn,
                    quantity,
                    unit_price: Number(book.price),
                    subtotal: line,
                };
            }),
            payment: {
                id,
                order_id: id,
                transaction_id: null,
                amount: Number((subtotal + shipping).toFixed(2)),
                method: fields.payment_method || 'cash_on_delivery',
                status: 'pending',
                paid_at: null,
            },
        };
        data.orders.push(order);
        saveData();
        return order;
    };

    const render = () => {
        const current = route();
        const [first, second, third] = current.segments;
        window.scrollTo({ top: 0, behavior: 'instant' in document.documentElement.style ? 'instant' : 'auto' });

        if (first === 'admin') {
            if (!isAdmin()) {
                go('/login');
                return;
            }
            if (!second) return renderAdminDashboard();
            if (second === 'books') return renderAdminBooks();
            if (second === 'orders' && third) return renderOrderDetail(third, true);
            if (second === 'orders') return renderAdminOrders();
            if (['categories', 'authors', 'customers'].includes(second)) return simpleAdminTable(second);
            if (second === 'reports') return renderReports();
            return renderAdminDashboard();
        }

        if (!first) return renderHome();
        if (first === 'books' && second) return renderBookDetail(second);
        if (first === 'books') return renderBooks(current.query);
        if (first === 'login') return renderLogin();
        if (first === 'register') return renderRegister();
        if (first === 'profile') return renderProfile();
        if (first === 'cart') return renderCart();
        if (first === 'checkout') return renderCheckout();
        if (first === 'orders' && second) return renderOrderDetail(second);
        if (first === 'orders') return renderOrders();
        if (['about', 'contact', 'faq', 'privacy', 'terms'].includes(first)) return pageContent(first);
        renderHome();
    };

    const addToCart = (bookId, quantity = 1) => {
        if (!isCustomer()) {
            go('/login');
            return;
        }
        const book = bookById(bookId);
        if (!book || book.stock < 1) {
            toast('This book is out of stock.', 'danger');
            return;
        }
        const existing = cart.find((item) => Number(item.bookId) === Number(bookId));
        if (existing) {
            existing.quantity = Math.min(book.stock, Number(existing.quantity) + Number(quantity));
        } else {
            cart.push({ bookId: Number(bookId), quantity: Math.min(book.stock, Number(quantity)) });
        }
        saveCart();
        render();
        toast('Book added to cart.');
    };

    const openQuickOrder = (bookId) => {
        if (!isCustomer()) {
            go('/login');
            return;
        }
        const book = bookById(bookId);
        const user = currentUser();
        quickOrderBookId = Number(bookId);
        const modal = document.getElementById('bookOrderModal');
        if (!book || !modal) return;
        const quantityInput = modal.querySelector('[data-order-quantity]');
        modal.querySelector('[data-order-cover]').src = book.image;
        modal.querySelector('[data-order-cover]').alt = `${book.title} cover`;
        modal.querySelector('[data-order-title]').textContent = book.title;
        modal.querySelector('[data-order-meta]').textContent = [authorById(book.author_id)?.name, categoryById(book.category_id)?.name].filter(Boolean).join(' / ');
        modal.querySelector('[data-order-price]').textContent = money(book.price);
        modal.querySelector('[data-order-stock]').textContent = `${book.stock} in stock`;
        modal.querySelector('#order_customer_name').value = user.name || '';
        modal.querySelector('#order_customer_phone').value = user.phone || '';
        modal.querySelector('#order_shipping_address').value = user.address || '';
        quantityInput.value = '1';
        quantityInput.max = String(Math.max(book.stock, 1));
        const refresh = () => {
            const quantity = Math.max(1, Number(quantityInput.value || 1));
            const subtotal = Number(book.price) * quantity;
            modal.querySelector('[data-order-shipping]').textContent = money(shippingFor(subtotal));
            modal.querySelector('[data-order-total]').textContent = money(subtotal + shippingFor(subtotal));
        };
        quantityInput.oninput = refresh;
        refresh();
        window.bootstrap.Modal.getOrCreateInstance(modal).show();
    };

    document.addEventListener('submit', (event) => {
        const form = event.target.closest('[data-static-form]');
        if (!form) return;
        event.preventDefault();
        const formData = new FormData(form);
        const fields = Object.fromEntries(formData.entries());
        const type = form.dataset.staticForm;

        try {
            if (type === 'nav-search') {
                go(`/books?search=${encodeURIComponent(fields.search || '')}`);
            }
            if (type === 'books-filter') {
                const params = new URLSearchParams();
                ['search', 'category', 'author', 'sort'].forEach((key) => fields[key] && params.set(key, fields[key]));
                go(`/books?${params.toString()}`);
            }
            if (type === 'login') {
                const user = data.users.find((candidate) => candidate.email.toLowerCase() === String(fields.email || '').toLowerCase());
                if (!user || fields.password !== data.demo_password || user.is_active === false) {
                    toast('Invalid demo credentials or inactive account.', 'danger');
                    return;
                }
                localStorage.setItem(USER_KEY, String(user.id));
                go(user.role === 'admin' ? '/admin' : '/');
            }
            if (type === 'register') {
                if (data.users.some((user) => user.email.toLowerCase() === String(fields.email).toLowerCase())) {
                    toast('That email is already registered in the demo.', 'danger');
                    return;
                }
                const id = Math.max(0, ...data.users.map((user) => Number(user.id))) + 1;
                const user = { id, name: fields.name, email: fields.email, role: 'customer', phone: '', address: '', is_active: true };
                data.users.push(user);
                saveData();
                localStorage.setItem(USER_KEY, String(id));
                go('/');
            }
            if (type === 'profile') {
                Object.assign(currentUser(), fields);
                saveData();
                render();
                toast('Profile updated.');
            }
            if (type === 'add-cart') {
                addToCart(Number(fields.book_id), Number(fields.quantity || 1));
            }
            if (type === 'checkout') {
                const order = createOrder(cart.map((item) => ({ bookId: item.bookId, quantity: item.quantity })), fields);
                cart = [];
                saveCart();
                go(`/orders/${order.id}`);
                setTimeout(() => toast('Order placed successfully.'), 50);
            }
            if (type === 'quick-order') {
                const order = createOrder([{ bookId: quickOrderBookId, quantity: Number(fields.quantity || 1) }], fields);
                window.bootstrap.Modal.getOrCreateInstance(document.getElementById('bookOrderModal')).hide();
                go(`/orders/${order.id}`);
                setTimeout(() => toast('Quick order placed successfully.'), 50);
            }
            if (type === 'admin-book-create') {
                const id = Math.max(0, ...data.books.map((book) => Number(book.id))) + 1;
                data.books.unshift({
                    id,
                    category_id: Number(fields.category_id),
                    author_id: Number(fields.author_id),
                    title: fields.title,
                    slug: `${slugify(fields.title)}-${id}`,
                    isbn: `978DEMO${String(id).padStart(6, '0')}`,
                    description: 'New static demo book created from the admin panel.',
                    price: Number(fields.price || 0),
                    stock: Number(fields.stock || 0),
                    cover_image: null,
                    image: 'assets/images/book-placeholder.svg',
                    publication_date: new Date().toISOString().slice(0, 10),
                    status: true,
                    featured: false,
                });
                saveData();
                renderAdminBooks();
                toast('Book added.');
            }
            if (type === 'admin-category-create' || type === 'admin-author-create') {
                const collection = type === 'admin-category-create' ? data.categories : data.authors;
                const id = Math.max(0, ...collection.map((item) => Number(item.id))) + 1;
                collection.push({
                    id,
                    name: fields.name,
                    slug: `${slugify(fields.name)}-${id}`,
                    description: fields.description || '',
                    biography: fields.description || '',
                    status: true,
                });
                saveData();
                render();
                toast('Admin data updated.');
            }
        } catch (error) {
            toast(error.message || 'Something went wrong.', 'danger');
        }
    });

    document.addEventListener('click', (event) => {
        const target = event.target.closest('[data-action]');
        if (!target) return;
        const action = target.dataset.action;

        if (action === 'logout') {
            localStorage.removeItem(USER_KEY);
            go('/');
        }
        if (action === 'reset-demo') {
            if (window.confirm('Reset localStorage demo data?')) {
                resetDemo();
                go('/');
            }
        }
        if (action === 'add-cart') addToCart(Number(target.dataset.bookId), 1);
        if (action === 'quick-order') openQuickOrder(Number(target.dataset.bookId));
        if (action === 'remove-cart') {
            cart = cart.filter((item) => Number(item.bookId) !== Number(target.dataset.bookId));
            saveCart();
            renderCart();
        }
        if (action === 'clear-cart') {
            cart = [];
            saveCart();
            renderCart();
        }
        if (action === 'admin-toggle-book') {
            const book = bookById(target.dataset.bookId);
            book.status = !book.status;
            saveData();
            renderAdminBooks();
        }
        if (action === 'admin-delete-book') {
            const book = bookById(target.dataset.bookId);
            book.deleted_at = book.deleted_at ? null : today();
            book.status = false;
            saveData();
            renderAdminBooks();
        }
        if (action === 'admin-toggle-user') {
            const user = data.users.find((item) => Number(item.id) === Number(target.dataset.userId));
            user.is_active = !user.is_active;
            saveData();
            simpleAdminTable('customers');
        }
        if (action === 'admin-toggle-category' || action === 'admin-toggle-author') {
            const collection = action.endsWith('category') ? data.categories : data.authors;
            const item = collection.find((candidate) => Number(candidate.id) === Number(target.dataset.id));
            item.status = !item.status;
            saveData();
            simpleAdminTable(action.endsWith('category') ? 'categories' : 'authors');
        }
    });

    document.addEventListener('change', (event) => {
        const target = event.target.closest('[data-action]');
        if (!target) return;
        const action = target.dataset.action;
        if (action === 'cart-qty') {
            const item = cart.find((entry) => Number(entry.bookId) === Number(target.dataset.bookId));
            const book = bookById(target.dataset.bookId);
            item.quantity = Math.max(1, Math.min(Number(book.stock), Number(target.value || 1)));
            saveCart();
            renderCart();
        }
        if (action === 'admin-stock') {
            const book = bookById(target.dataset.bookId);
            book.stock = Math.max(0, Number(target.value || 0));
            saveData();
            renderAdminBooks();
        }
        if (action === 'admin-order-status' || action === 'admin-payment-status') {
            const order = data.orders.find((item) => Number(item.id) === Number(target.dataset.orderId));
            if (action === 'admin-order-status') order.order_status = target.value;
            if (action === 'admin-payment-status') {
                order.payment_status = target.value;
                order.payment.status = target.value;
                order.payment.paid_at = target.value === 'paid' ? today() : null;
            }
            order.updated_at = today();
            saveData();
            renderAdminOrders();
        }
    });

    const init = async () => {
        try {
            const response = await fetch('assets/data/seed.json', { cache: 'no-store' });
            seed = await response.json();
            data = JSON.parse(localStorage.getItem(DATA_KEY) || 'null');
            cart = JSON.parse(localStorage.getItem(CART_KEY) || '[]');
            if (!data || data.version !== seed.version) {
                data = clone(seed);
                saveData();
            }
            window.addEventListener('hashchange', render);
            render();
        } catch (error) {
            app.innerHTML = `<main class="container py-5"><div class="surface-card p-4"><h1 class="h4">Could not load the static demo</h1><p class="text-muted mb-0">${esc(error.message)}</p></div></main>`;
        }
    };

    init();
})();
