const accessToken = localStorage.getItem("accessToken");
const refreshToken = localStorage.getItem("refreshToken");


const root = ReactDOM.createRoot(document.getElementById("root"));




if (accessToken && refreshToken) {
    root.render(<Main />);
} else {
    root.render(<App />);
}

