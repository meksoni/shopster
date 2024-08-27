(() => {
    "use strict";

    const storedTheme = localStorage.getItem("theme");

    const getPreferredTheme = () => {
        if (storedTheme) {
            return storedTheme;
        }

        return window.matchMedia("(prefers-color-scheme: dark)").matches
            ? "dark"
            : "light";
    };

    const setTheme = function (theme) {
        if (
            theme === "auto" &&
            window.matchMedia("(prefers-color-scheme: dark)").matches
        ) {
            document.documentElement.setAttribute("data-bs-theme", "dark");
        } else {
            document.documentElement.setAttribute("data-bs-theme", theme);
        }
    };

    setTheme(getPreferredTheme());

    const showActiveTheme = (theme) => {
        const activeThemeIcon = document.querySelector(
            ".theme-icon-active .material-symbols-rounded"
        );
        const btnToActive = document.querySelector(
            `[data-bs-theme-value="${theme}"]`
        );

        if (activeThemeIcon && btnToActive) {
            const svgOfActiveBtn = btnToActive.querySelector(
                ".theme-icon .material-symbols-rounded"
            );
            if (svgOfActiveBtn) {
                document
                    .querySelectorAll("[data-bs-theme-value]")
                    .forEach((element) => {
                        element.classList.remove("active");
                    });

                btnToActive.classList.add("active");
                activeThemeIcon.setAttribute(
                    "href",
                    svgOfActiveBtn.getAttribute("href")
                );
            }
        }
    };

    window
        .matchMedia("(prefers-color-scheme: dark)")
        .addEventListener("change", () => {
            if (storedTheme !== "light" || storedTheme !== "dark") {
                setTheme(getPreferredTheme());
            }
        });

    window.addEventListener("DOMContentLoaded", () => {
        showActiveTheme(getPreferredTheme());

        document.querySelectorAll("[data-bs-theme-value]").forEach((toggle) => {
            toggle.addEventListener("click", () => {
                const theme = toggle.getAttribute("data-bs-theme-value");
                localStorage.setItem("theme", theme);
                setTheme(theme);
                showActiveTheme(theme);
            });
        });
    });
})();
