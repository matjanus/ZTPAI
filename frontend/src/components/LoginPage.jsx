
// src/components/LoginPage.jsx
import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import api from "../services/api";
import "../styles/LoginPage.css";

const LoginPage = () => {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState(null);
  const navigate = useNavigate();

  const handleLogin = async (e) => {
    e.preventDefault();
    setError(null);

    try {
      const response = await api.post("/api/login", { email, password });
      alert("Zalogowano pomyślnie: " + response.data.user);
      // Tutaj możesz dodać logikę zapisywania tokena JWT (jeśli używasz)
    } catch (err) {
      setError(err.response?.data?.error || "Wystąpił błąd");
    }
  };

  return (
    <div className="login-container">
      <div className="login-left">
        <h1>Bezili</h1>
      </div>
      <div className="login-right">
        {error && <p className="error-message">{error}</p>}
        <form onSubmit={handleLogin}>
          <input
            type="email"
            placeholder="Email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
          />
          <input
            type="password"
            placeholder="Haasasasło"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            required
          />
          <button type="submit">Zaloasasasasguj</button>
        </form>
        <button className="register-button" onClick={() => navigate("/register")}>
          Zarejestruj się
        </button>
      </div>
    </div>
  );
};

export default LoginPage;