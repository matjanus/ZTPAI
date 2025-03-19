import { BrowserRouter as Router, Routes, Route } from "react-router-dom";;
// import Login from "./pages/Login";
import LoginPage from "./components/LoginPage";

function App() {
  return (
    <Router>
      <Routes>
      <Route path="/" element={<LoginPage />} />
      <Route path="/register" element={<h1>Rejestracja (do zaimplementowania)</h1>} />
     
      </Routes>
    </Router>
  );
}

export default App;
