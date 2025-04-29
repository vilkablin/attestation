const animateObject = {
    modal: {
        open: {
            opacity: 1,
            scale: 1,
        },
        close: {
            opacity: 0,
            scale: 0.92,
        },
    },

    combobox: {
        open: {
            opacity: 1,
            y: 0,
        },
        close: {
            y: -10,
            opacity: 0,
        },
    },

    autocomplete: {
        open: {
            opacity: 1,
        },
        close: {
            opacity: 0,
        },
    },

    animateTooltip: {
        show: {
            opacity: 1,
            scale: 1,
        },
        hide: {
            opacity: 0,
            scale: 0.7,
        },
    },

    heightWithOpacity: {
        hide: {
            opacity: 0,
            height: 0,
        },
        show: {
            opacity: 1,
            height: "auto",
        },
    },

    comboboxMarkScale: {
        open: {
            scale: 1,
        },
        close: {
            scale: 0,
        },
    },

    faqItem: {
        open: { height: "auto", opacity: 1 },
        close: { height: 0, opacity: 0, overflow: "hidden" },
    },
};

export const animateHelper = (variant) => animateObject[variant];
