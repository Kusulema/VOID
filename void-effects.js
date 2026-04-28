/* ======================================================================
   THE VOID — TERMINAL MALFUNCTION SYSTEM
   ====================================================================== */

const clickSound = document.getElementById('terminalClick');

const playClick = () => {
    if (!clickSound) {
        return;
    }

    try {
        clickSound.currentTime = 0;
        const promise = clickSound.play();
        if (promise && typeof promise.catch === 'function') {
            promise.catch(() => {});
        }
    } catch (error) {
        // ignore autoplay errors
    }
};

const getCart = () => {
    try {
        return JSON.parse(localStorage.getItem('voidCart') || '[]');
    } catch (error) {
        return [];
    }
};

const setCart = (cart) => {
    localStorage.setItem('voidCart', JSON.stringify(cart));
};

const updateCartBadge = () => {
    const cart = getCart();
    document.querySelectorAll('[data-cart-count]').forEach((node) => {
        node.textContent = cart.reduce((total, item) => total + item.qty, 0);
    });
};

const renderCart = () => {
    const cartList = document.querySelector('[data-cart-list]');
    const cartTotal = document.querySelector('[data-cart-total]');
    const cartEmpty = document.querySelector('[data-cart-empty]');

    if (!cartList || !cartTotal) {
        return;
    }

    const cart = getCart();
    cartList.innerHTML = '';

    if (!cart.length) {
        if (cartEmpty) {
            cartEmpty.style.display = 'block';
        }
        cartTotal.textContent = '0';
        return;
    }

    if (cartEmpty) {
        cartEmpty.style.display = 'none';
    }

    let total = 0;

    cart.forEach((item) => {
        const price = Number(item.price) || 0;
        total += price * item.qty;

        const row = document.createElement('div');
        row.className = 'cart-item';
        row.innerHTML = `
            <div>
                <strong>${item.title}</strong>
                <span class="account-copy">Qty ${item.qty}</span>
            </div>
            <div class="release-price">${price ? price.toFixed(2) + ' €' : '—'}</div>
        `;
        cartList.appendChild(row);
    });

    cartTotal.textContent = total.toFixed(2);
};

const openPopup = () => {
    const popup = document.getElementById('newsletterPopup');
    if (!popup) {
        return;
    }

    popup.classList.add('is-open');
    popup.setAttribute('aria-hidden', 'false');
};

const closePopup = () => {
    const popup = document.getElementById('newsletterPopup');
    if (!popup) {
        return;
    }

    popup.classList.remove('is-open');
    popup.setAttribute('aria-hidden', 'true');
    sessionStorage.setItem('voidNewsletterClosed', '1');
};

document.addEventListener('click', (event) => {
    const clickableCard = event.target.closest('[data-card-link]');
    const addToCartButton = event.target.closest('[data-add-to-cart]');
    const popupClose = event.target.closest('[data-popup-close]');
    const interactive = event.target.closest('a, button, input, select, textarea, label');

    if (clickableCard && !interactive) {
        const href = clickableCard.getAttribute('data-href');
        if (href) {
            window.location.href = href;
            playClick();
        }
        return;
    }

    if (addToCartButton) {
        event.preventDefault();
        const item = {
            id: addToCartButton.dataset.id,
            title: addToCartButton.dataset.title,
            price: addToCartButton.dataset.price || '0',
            qty: 1,
        };

        const cart = getCart();
        const existing = cart.find((entry) => entry.id === item.id);
        if (existing) {
            existing.qty += 1;
        } else {
            cart.push(item);
        }

        setCart(cart);
        updateCartBadge();
        renderCart();
        playClick();
        return;
    }

    if (popupClose) {
        closePopup();
        return;
    }

    if (event.target.classList && event.target.classList.contains('newsletter-popup')) {
        closePopup();
        return;
    }

    if (event.target.tagName === 'A' || event.target.tagName === 'BUTTON') {
        playClick();
    }
});

setInterval(() => {
    const boxes = document.querySelectorAll('.newsBox, .divBox, .review-card, .about-panel, .contact-panel, .cart-panel, .account-panel');

    boxes.forEach((box) => {
        if (Math.random() < 0.05) {
            box.style.opacity = '0.82';
            setTimeout(() => {
                box.style.opacity = '1';
            }, 60 + Math.random() * 110);
        }
    });
}, 700);

setInterval(() => {
    if (Math.random() < 0.92) {
        document.body.classList.add('glitch');
        setTimeout(() => document.body.classList.remove('glitch'), 180);
    }
}, 3200);

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('cultForm');
    const cursor = document.querySelector('.init-cursor');

    if (cursor && form) {
        setTimeout(() => {
            cursor.style.display = 'none';
            form.classList.remove('hidden');
        }, 3600);
    }

    updateCartBadge();
    renderCart();

    if (document.body.dataset.page === 'home' && !sessionStorage.getItem('voidNewsletterClosed')) {
        setTimeout(openPopup, 850);
    }
});
