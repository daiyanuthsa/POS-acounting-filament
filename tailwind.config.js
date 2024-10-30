/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.jsx",
    ],
    theme: {
        extend: {
            colors: {
                "cust-yellow": "#FEE142",
                "cust-yellow2" : "#FFDD90"
            },
            fontFamily: {
                dmsans: ["DM Sans", "sans-serif"],
                montserrat: ["Montserrat", "sans-serif"],
                plusJakarta: ["Plus Jakarta Sans", "sans-serif"]
            },
        },
    },
    plugins: [],
};
