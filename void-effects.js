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

const escapeHtml = (value) => String(value)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');

const initReviewCarousel = () => {
    const viewport = document.querySelector('[data-review-carousel]');
    const track = viewport ? viewport.querySelector('.review-carousel-track') : null;
    const prevButton = document.querySelector('[data-review-prev]');
    const nextButton = document.querySelector('[data-review-next]');
    const form = document.querySelector('[data-review-form]');

    if (!viewport || !track) {
        return;
    }

    const ratingButtons = form ? Array.from(form.querySelectorAll('[data-rating-value]')) : [];
    const ratingInput = form ? form.querySelector('[data-review-rating]') : null;
    const textInput = form ? form.querySelector('[data-review-text]') : null;
    let activeRating = Number(ratingInput && ratingInput.value) || 5;
    let originalCards = Array.from(track.children).map((card) => card.cloneNode(true));
    let cloneCount = 0;
    let index = 0;
    let autoTimer = null;

    const setRating = (value) => {
        activeRating = Math.max(1, Math.min(5, Number(value) || 5));

        if (ratingInput) {
            ratingInput.value = String(activeRating);
        }

        ratingButtons.forEach((button) => {
            const buttonValue = Number(button.dataset.ratingValue) || 0;
            button.classList.toggle('is-selected', buttonValue <= activeRating);
        });
    };

    const createCard = (review) => {
        const card = document.createElement('article');
        card.className = 'review-card review-carousel-card';
        const rating = Math.max(1, Math.min(5, Number(review.rating) || 5));

        card.innerHTML = `
            <div class="review-mark"><img src="img/skull.png" alt="" aria-hidden="true"></div>
            <p>${escapeHtml(review.text || '')}</p>
            <div class="review-rating" aria-label="Rating ${rating} out of 5">
                ${Array.from({ length: 5 }, (_, skullIndex) => {
                    const activeClass = skullIndex < rating ? ' is-active' : '';
                    return `<img src="img/skull.png" alt="" class="review-rating-skull${activeClass}">`;
                }).join('')}
            </div>
            <span>${escapeHtml(review.date || 'Featured review')}</span>
        `;

        return card;
    };

    const rebuildCarousel = () => {
        const cards = originalCards.map((card) => card.cloneNode(true));
        cloneCount = Math.min(3, cards.length);

        track.innerHTML = '';
        cards.slice(-cloneCount).forEach((card) => track.appendChild(card.cloneNode(true)));
        cards.forEach((card) => track.appendChild(card.cloneNode(true)));
        cards.slice(0, cloneCount).forEach((card) => track.appendChild(card.cloneNode(true)));

        index = cloneCount;
    };

    const getOffset = () => {
        const card = track.children[index] || track.children[cloneCount];

        if (!card) {
            return 0;
        }

        return card.offsetLeft;
    };

    const syncPosition = (animate = false) => {
        track.style.transition = animate ? 'transform 650ms ease' : 'none';
        track.style.transform = `translateX(-${getOffset()}px)`;
    };

    const move = (direction) => {
        index += direction;
        syncPosition(true);
    };

    const normalize = () => {
        const originalLength = originalCards.length;

        if (index >= cloneCount + originalLength) {
            index = cloneCount;
            syncPosition(false);
        }

        if (index < cloneCount) {
            index = cloneCount + originalLength - 1;
            syncPosition(false);
        }
    };

    const restartAuto = () => {
        if (autoTimer) {
            clearInterval(autoTimer);
        }

        autoTimer = setInterval(() => move(1), 4500);
    };

    ratingButtons.forEach((button) => {
        button.addEventListener('click', () => {
            setRating(Number(button.dataset.ratingValue) || 5);
        });
    });

    prevButton?.addEventListener('click', () => move(-1));
    nextButton?.addEventListener('click', () => move(1));

    track.addEventListener('transitionend', normalize);

    viewport.addEventListener('mouseenter', () => {
        if (autoTimer) {
            clearInterval(autoTimer);
            autoTimer = null;
        }
    });

    viewport.addEventListener('mouseleave', restartAuto);

    window.addEventListener('resize', () => syncPosition(false));

    if (form) {
        form.addEventListener('submit', (event) => {
            event.preventDefault();

            const text = textInput ? textInput.value.trim() : '';
            if (!text) {
                return;
            }

            originalCards.unshift(createCard({
                text,
                date: 'Just now',
                rating: activeRating,
            }));

            rebuildCarousel();
            setRating(activeRating);
            syncPosition(false);

            if (textInput) {
                textInput.value = '';
            }
        });
    }

    rebuildCarousel();
    setRating(activeRating);
    syncPosition(false);
    restartAuto();
};

const openPopup = () => {
    const popup = document.getElementById('newsletterPopup');
    if (!popup) {
        return;
    }

    if (popup.parentElement !== document.documentElement) {
        document.documentElement.appendChild(popup);
    }

    popup.classList.add('is-open');
    popup.setAttribute('aria-hidden', 'false');
    document.documentElement.classList.add('modal-scroll-lock');
    document.body.classList.add('modal-scroll-lock');
};

const closePopup = () => {
    const popup = document.getElementById('newsletterPopup');
    if (!popup) {
        return;
    }

    popup.classList.remove('is-open');
    popup.setAttribute('aria-hidden', 'true');
    sessionStorage.setItem('voidNewsletterClosed', '1');
    document.documentElement.classList.remove('modal-scroll-lock');
    document.body.classList.remove('modal-scroll-lock');
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

document.addEventListener('click', (event) => {
    const heartLink = event.target.closest('a.wishlist-heart');
    if (!heartLink) {
        return;
    }

    event.preventDefault();

    const url = heartLink.href;
    if (!url) {
        return;
    }

    const isActive = heartLink.classList.contains('active');
    fetch(url, {
        method: 'GET',
        credentials: 'same-origin',
        redirect: 'follow',
    }).then((response) => {
        if (!response.ok) {
            throw new Error('Wishlist request failed');
        }
        heartLink.classList.toggle('active', !isActive);
        heartLink.textContent = isActive ? '♡' : '♥';
        heartLink.style.color = isActive ? 'rgba(255, 255, 255, 0.78)' : '#ff4d4d';
    }).catch(() => {
        // keep the existing state if the request fails
    });
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
    const newsletterPopup = document.getElementById('newsletterPopup');

    sessionStorage.removeItem('voidNewsletterClosed');

    if (cursor && form) {
        setTimeout(() => {
            cursor.style.display = 'none';
            form.classList.remove('hidden');
        }, 3600);
    }

    if (newsletterPopup && !sessionStorage.getItem('voidNewsletterClosed')) {
        setTimeout(() => {
            if (!sessionStorage.getItem('voidNewsletterClosed')) {
                openPopup();
            }
        }, 10000);
    }

    updateCartBadge();
    renderCart();
    initReviewCarousel();
});
