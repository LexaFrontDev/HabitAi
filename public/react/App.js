const { createRoot } = ReactDOM;
const { BrowserRouter, Routes, Route } = ReactRouterDOM;

const Profile = () => React.createElement('h1', null, 'Профиль');

const App = () => {
    return React.createElement(
        BrowserRouter,
        null,
        React.createElement(
            Routes,
            null,
            React.createElement(Route, { path: "/profile", element: React.createElement(Profile) })
        )
    );
};

const root = createRoot(document.getElementById("react-root"));
root.render(React.createElement(App));
