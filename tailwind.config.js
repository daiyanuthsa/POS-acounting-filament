/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.jsx",
    ],
    theme: {
        extend: {
            keyframes: {
                scroll: {
                    "0%": { transform: "translateX(0)" },
                    "100%": { transform: "translateX(-100%)" },
                },
            },
            animation: {
                scroll: "scroll 30s linear infinite",
            },
            colors: {
                "cust-yellow": "#FEE142",
            },
            fontFamily: {
                dmsans: ["DM Sans", "sans-serif"],
                montserrat: ["Montserrat", "sans-serif"],
            },
        },
    },
    plugins: [],
};
