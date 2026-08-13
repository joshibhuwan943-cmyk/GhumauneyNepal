const destinations = window.destinationData || [];

const fallbackImage = "data:image/svg+xml;charset=UTF-8," + encodeURIComponent(`
    <svg xmlns="http://www.w3.org/2000/svg" width="800" height="500" viewBox="0 0 800 500">
        <rect width="800" height="500" fill="#0e1b2f"/>
        <rect x="30" y="30" width="740" height="440" rx="28" fill="#122036" stroke="#f59e0b" stroke-width="3"/>
        <path d="M120 360 L280 220 L360 290 L470 170 L680 360" fill="none" stroke="#f59e0b" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/>
        <circle cx="240" cy="190" r="34" fill="#f59e0b"/>
        <text x="400" y="420" text-anchor="middle" font-family="'Outfit', sans-serif" font-size="28" font-weight="bold" fill="#f8fafc">GhumauneyNepal Destination</text>
    </svg>
`);

function getImageUrl(path) {
    return path ? encodeURI(path) : path;
}

const plannerForm = document.getElementById("tripPlannerForm");
const plannerResult = document.getElementById("plannerResult");
const contactForm = document.getElementById("contactForm");
const contactResult = document.getElementById("contactResult");
const navToggle = document.querySelector(".nav-toggle");
const mainNav = document.querySelector(".main-nav");
const pageLoader = document.getElementById("pageLoader");
const destinationModal = document.getElementById("destinationModal");
const modalContent = document.getElementById("modalContent");
const closeModalButton = document.getElementById("closeModal");
const wishlistSummary = document.getElementById("wishlistSummary");
const featuredDestinationsContainer = document.getElementById("featuredDestinations");
let wishlist = [];
let currentProvince = "all";
let currentCategory = "all";
let currentBudget = "all";
let currentSearch = "";

function initLoader() {
    if (!pageLoader) return;

    window.addEventListener("load", () => {
        window.setTimeout(() => {
            pageLoader.classList.add("is-hidden");
            document.body.classList.remove("is-loading");
        }, 350);
    });
}

function initPlanner() {
    if (!plannerForm || !plannerResult) return;

    plannerForm.addEventListener("submit", (event) => {
        event.preventDefault();

        const destination = document.getElementById("destination").value;
        const budget = document.getElementById("budget").value;
        const travelers = document.getElementById("travelers").value;
        const days = document.getElementById("days").value;
        const interest = document.getElementById("interest").value;

        plannerResult.className = "planner-result";

        if (!destination || !budget || !travelers || !days || !interest) {
            plannerResult.className = "planner-result error is-visible";
            plannerResult.textContent = "Please complete all trip planner fields before creating your itinerary.";
            return;
        }

        plannerResult.className = "planner-result success is-visible";
        plannerResult.textContent = `Your ${budget.toLowerCase()} trip to ${destination} for ${travelers} traveler(s) over ${days} day(s) focused on ${interest.toLowerCase()} is ready to explore!`;
    });
}

function initContactForm() {
    if (!contactForm || !contactResult) return;

    contactForm.addEventListener("submit", (event) => {
        event.preventDefault();
        const name = document.getElementById("contactName").value;
        
        contactResult.className = "planner-result success is-visible";
        contactResult.textContent = `Thank you, ${name}! Your inquiry has been received. We will get back to you shortly.`;
        contactForm.reset();
    });
}

function initNavigation() {
    if (navToggle && mainNav) {
        navToggle.addEventListener("click", () => {
            mainNav.classList.toggle("nav-open");
            const expanded = mainNav.classList.contains("nav-open");
            navToggle.setAttribute("aria-expanded", expanded);
        });
    }

    window.addEventListener("scroll", () => {
        if (mainNav) {
            mainNav.classList.toggle("scrolled", window.scrollY > 20);
        }
    });

    document.querySelectorAll('a[href^="#"]').forEach((link) => {
        link.addEventListener("click", (event) => {
            const targetId = link.getAttribute("href");
            if (!targetId || targetId === "#") return;

            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                event.preventDefault();
                targetElement.scrollIntoView({ behavior: "smooth", block: "start" });
            }
        });
    });
}

function initReveal() {
    const revealItems = document.querySelectorAll(".reveal, .reveal-card");
    if (!revealItems.length) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add("is-visible");
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    revealItems.forEach((item, index) => {
        item.style.transitionDelay = `${Math.min(index * 0.05, 0.25)}s`;
        observer.observe(item);
    });
}

/* Subtle 3D Mouse Micro-Tilt Effect */
function init3DTilt() {
    const tiltCards = document.querySelectorAll(".destination-card, .content-card, .package-card, .feature-card, .culture-card, .stats-card, .province-card, .founder-card, .hotel-card");

    tiltCards.forEach((card) => {
        card.addEventListener("mousemove", (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            const rotateX = ((y - centerY) / centerY) * -5;
            const rotateY = ((x - centerX) / centerX) * 5;

            card.style.transform = `perspective(1000px) rotateX(${rotateX.toFixed(2)}deg) rotateY(${rotateY.toFixed(2)}deg) translateY(-8px) translateZ(12px)`;
        });

        card.addEventListener("mouseleave", () => {
            card.style.transform = "";
        });
    });
}

function openModal(destination) {
    if (!destinationModal || !modalContent) return;

    const modalImageUrl = getImageUrl(destination.image);

    modalContent.innerHTML = `
        <div class="modal-content-grid">
            <img class="modal-image" src="${modalImageUrl}" alt="${destination.name}" loading="lazy">
            <div>
                <span class="destination-province">${destination.province}</span>
                <h2 id="modalTitle" style="margin-top: 10px; font-size: 1.8rem; color: var(--text-main);">${destination.name}</h2>
                <p style="margin: 12px 0; color: var(--text-muted);">${destination.description}</p>
                <div class="destination-meta">
                    <span class="destination-badge">${destination.category}</span>
                    <span class="destination-badge">${destination.budget}</span>
                </div>
                ${destination.district ? `<p class="modal-meta"><strong>District:</strong> ${destination.district}</p>` : ""}
                <p class="modal-meta"><strong>Best time to visit:</strong> ${destination.bestTime}</p>
                ${destination.duration ? `<p class="modal-meta"><strong>Suggested duration:</strong> ${destination.duration}</p>` : ""}
                ${destination.locationNote ? `<p class="modal-meta" style="color: var(--accent-amber); font-style: italic;">${destination.locationNote}</p>` : ""}
                <ul class="timeline-list">
                    ${destination.highlights.map((item) => `<li>${item}</li>`).join("")}
                </ul>
                <a href="contact.html" class="btn btn-small" style="margin-top: 20px;">Plan Visit Here</a>
            </div>
        </div>
    `;

    const modalImg = modalContent.querySelector('.modal-image');
    if (modalImg) {
        modalImg.addEventListener('error', () => {
            modalImg.onerror = null;
            modalImg.src = fallbackImage;
            modalImg.alt = `${destination.name} image unavailable`;
        });
    }

    destinationModal.classList.add("is-open");
    destinationModal.setAttribute("aria-hidden", "false");
    document.body.classList.add("modal-open");
}

function closeModal() {
    if (!destinationModal) return;

    destinationModal.classList.remove("is-open");
    destinationModal.setAttribute("aria-hidden", "true");
    document.body.classList.remove("modal-open");
}

function updateWishlistSummary() {
    if (wishlistSummary) {
        wishlistSummary.textContent = `Wishlist (${wishlist.length})`;
    }
}

function renderFeaturedDestinations() {
    if (!featuredDestinationsContainer) return;

    const featured = destinations.slice(0, 6);
    featuredDestinationsContainer.innerHTML = featured.map((destination) => {
        const imageUrl = getImageUrl(destination.image);
        return `
        <article class="content-card reveal-card">
            <div class="card-image-wrapper">
                <img class="card-image" src="${imageUrl}" alt="${destination.name}" loading="lazy">
            </div>
            <div class="card-content">
                <div class="destination-meta">
                    <span class="destination-badge">${destination.category}</span>
                    <span class="destination-badge">${destination.budget}</span>
                </div>
                <h3>${destination.name}</h3>
                <p>${destination.description}</p>
                <a href="destinations.html" class="text-link">Explore</a>
            </div>
        </article>
    `;
    }).join("");

    const featuredImages = featuredDestinationsContainer.querySelectorAll('img.card-image');
    featuredImages.forEach(img => {
        img.addEventListener('error', () => {
            img.onerror = null;
            img.src = fallbackImage;
        });
    });

    init3DTilt();
}

function initDestinations() {
    const container = document.getElementById("destinationContainer");
    const searchInput = document.getElementById("searchInput");
    const categoryFilter = document.getElementById("categoryFilter");
    const budgetFilter = document.getElementById("budgetFilter");

    if (!container) return;

    function filterDestinations() {
        return destinations.filter((destination) => {
            const normalizedProvince = destination.province.split(" ")[0];
            const matchesProvince = currentProvince === "all" || normalizedProvince === currentProvince || destination.province === currentProvince;
            const matchesCategory = currentCategory === "all" || destination.category === currentCategory;
            const matchesBudget = currentBudget === "all" || destination.budget === currentBudget;
            const matchesSearch = destination.name.toLowerCase().includes(currentSearch.toLowerCase()) || destination.description.toLowerCase().includes(currentSearch.toLowerCase());
            return matchesProvince && matchesCategory && matchesBudget && matchesSearch;
        });
    }

    function displayDestinations(list) {
        container.innerHTML = "";

        if (!list.length) {
            container.innerHTML = '<div class="planner-card" style="grid-column: 1/-1; text-align: center;"><p>No destinations match the current filters.</p></div>';
            return;
        }

        list.forEach(destination => {
            const card = document.createElement("div");
            card.className = "destination-card";

            const inWishlist = wishlist.includes(destination.name);
            card.innerHTML = `
                <button class="wishlist-btn ${inWishlist ? "is-active" : ""}" type="button" data-name="${destination.name}" aria-label="Save to wishlist">♡</button>
                <div class="card-image-wrapper">
                    <img class="destination-image" src="${getImageUrl(destination.image)}" alt="${destination.name}" loading="lazy">
                </div>

                <div class="destination-info">
                    <div class="destination-meta">
                        <span class="destination-badge">${destination.category}</span>
                        <span class="destination-badge">${destination.budget}</span>
                    </div>
                    <h3>${destination.name}</h3>
                    <p>${destination.description}</p>
                    <div class="destination-actions">
                        <span class="destination-province">${destination.province}</span>
                        <button class="text-link" type="button" data-open="${destination.name}">View details</button>
                    </div>
                </div>
            `;

            const image = card.querySelector("img");
            image.addEventListener("error", () => {
                image.onerror = null;
                image.src = fallbackImage;
            });

            card.querySelector(".wishlist-btn").addEventListener("click", (event) => {
                event.stopPropagation();
                const destinationName = event.currentTarget.dataset.name;
                if (wishlist.includes(destinationName)) {
                    wishlist = wishlist.filter(item => item !== destinationName);
                } else {
                    wishlist.push(destinationName);
                }
                updateWishlistSummary();
                displayDestinations(filterDestinations());
            });

            card.querySelector("[data-open]").addEventListener("click", (event) => {
                event.stopPropagation();
                const selectedDestination = destinations.find(item => item.name === event.currentTarget.dataset.open);
                if (selectedDestination) {
                    openModal(selectedDestination);
                }
            });

            card.addEventListener("click", () => {
                openModal(destination);
            });

            container.appendChild(card);
        });

        init3DTilt();
    }

    function renderDestinations() {
        displayDestinations(filterDestinations());
    }

    renderDestinations();

    const buttons = document.querySelectorAll(".province-btn");
    buttons.forEach(button => {
        button.addEventListener("click", () => {
            buttons.forEach(btn => btn.classList.remove("active"));
            button.classList.add("active");
            currentProvince = button.dataset.province;
            renderDestinations();
        });
    });

    if (searchInput) {
        searchInput.addEventListener("input", (event) => {
            currentSearch = event.target.value.trim();
            renderDestinations();
        });
    }

    if (categoryFilter) {
        categoryFilter.addEventListener("change", (event) => {
            currentCategory = event.target.value;
            renderDestinations();
        });
    }

    if (budgetFilter) {
        budgetFilter.addEventListener("change", (event) => {
            currentBudget = event.target.value;
            renderDestinations();
        });
    }

    if (wishlistSummary) {
        wishlistSummary.addEventListener("click", () => {
            const wishlistList = wishlist.length ? wishlist.join(", ") : "No destinations saved yet";
            alert(`Saved Wishlist (${wishlist.length}):\n\n${wishlistList}`);
        });
    }

    if (closeModalButton) {
        closeModalButton.addEventListener("click", closeModal);
    }

    if (destinationModal) {
        destinationModal.addEventListener("click", (event) => {
            if (event.target === destinationModal) {
                closeModal();
            }
        });
    }

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape") {
            closeModal();
        }
    });

    updateWishlistSummary();
}

initLoader();
initPlanner();
initContactForm();
initNavigation();
initReveal();
renderFeaturedDestinations();
initDestinations();
init3DTilt();