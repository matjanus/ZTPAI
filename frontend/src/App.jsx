import { BrowserRouter as Router, Routes, Route } from "react-router-dom";;
// import Login from "./pages/Login";
import LoginPage from "./components/LoginPage";
import RegisterForm from "./components/RegisterForm";

function App() {
  return (
    <Router>
      <Routes>
      <Route path="/" element={<LoginPage />} />
      <Route path="/register" element={<RegisterForm />} />
      </Routes>
    </Router>
  );
}

export default App;
