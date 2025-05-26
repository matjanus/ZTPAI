import { useState } from "react";
import { useNavigate } from "react-router-dom";
import "./LoginForm.css";

export default function LoginForm() {
  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const navigate = useNavigate();

  const handleLogin = async (e) => {
    e.preventDefault();
    setError("");

    try {
      const res = await fetch("http://127.0.0.1:8000/api/login_check", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ username, password }),
      });

      if (!res.ok) {
        setError("Nieprawidłowy login lub hasło.");
        return;
      }

      const data = await res.json();
      localStorage.setItem("token", data.token);
      navigate("/"); // redirect to homepage
    } catch {
      setError("Błąd połączenia z serwerem.");
    }
  };

  return (
    <form className="login-form" onSubmit={handleLogin}>
      <h2>Zaloguj się</h2>
      {error && <p className="error">{error}</p>}

      <label>Nazwa użytkownika</label>
      <input
        type="text"
        value={username}
        onChange={(e) => setUsername(e.target.value)}
        required
      />

      <label>Hasło</label>
      <input
        type="password"
        value={password}
        onChange={(e) => setPassword(e.target.value)}
        required
      />

      <button type="submit">SignIn</button>
      <button  onClick={() => navigate("/register")}>SignUp</button>
    </form>
  );
}