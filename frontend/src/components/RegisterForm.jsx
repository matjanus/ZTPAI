import { useState } from 'react';
import axios from 'axios';
import './AuthForm.css';

export default function RegisterForm() {
  const [form, setForm] = useState({ username: '', password: '', repeatPassword: '', email: '' });
  const [message, setMessage] = useState('');

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const validateForm = () => {
    if (form.username.length < 5) {
      return 'The user name must be at least 5 characters long.';
    }

    if (form.password.length < 8) {
      return 'The password must be at least 8 characters long.';
    }

    if (!/[a-z]/.test(form.password) || !/[A-Z]/.test(form.password)) {
      return 'The password must contain at least 8 characters, including lowercase and uppercase letters.';
    }

    if (form.password !== form.repeatPassword) {
      return 'The passwords are not the same.';
    }

    return null;
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    const error = validateForm();
    if (error) {
      setMessage(error);
      return;
    }

    try {
      const response = await axios.post('http://127.0.0.1:8000/register', form);
      setMessage(`Zarejestrowano użytkownika: ${response.data.username}`);
    } catch (error) {
      if (error.response?.data?.error) {
        setMessage(`Error: ${error.response.data.error}`);
      } else {
        setMessage('Server connection error.');
      }
    }
  };

  return (
    <div className="auth-form-container">
      <form className="auth-form" onSubmit={handleSubmit}>
        <h2>Create account</h2>
        <label>Username</label>
        <input
          type="text"
          name="username"
          placeholder="Username"
          value={form.username}
          onChange={handleChange}
          required
        />
        <label>Email</label>
        <input
          type="email"
          name="email"
          placeholder="Email"
          value={form.email}
          onChange={handleChange}
        />
        <label>Password</label>
        <input
          type="password"
          name="password"
          placeholder="Password"
          value={form.password}
          onChange={handleChange}
          required
        />
        <label>Repeat Password</label>
        <input
          type="password"
          name="repeatPassword"
          placeholder="RepeatPassword"
          value={form.repeatPassword}
          onChange={handleChange}
          required
        />
        <button type="submit">Create</button>
        {message && <p className="error">{message}</p>}
      </form>

    </div>
  );
}
