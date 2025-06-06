import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { useEffect } from "react";
import "./AuthForm.css";

export default function LoginForm( { expired = false } ) {
  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const navigate = useNavigate();


  useEffect(() => {
    if (expired) {
      setError("Your session has expired. Log in again.");
    }
  }, [expired]);

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
        setError("Invalid login or password.");
        return;
      }

      const data = await res.json();
      localStorage.setItem("token", data.token);
      localStorage.setItem("refreshToken", data.refresh_token);
      navigate("/"); 
    } catch {
      setError("Server connection error.");
    }
  };

  return (
    <form className="auth-form" onSubmit={handleLogin}>
      {error && <p className="error">{error}</p>}

      <label>Username</label>
      <input
        type="text"
        value={username}
        onChange={(e) => setUsername(e.target.value)}
        required
      />

      <label>Password</label>
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