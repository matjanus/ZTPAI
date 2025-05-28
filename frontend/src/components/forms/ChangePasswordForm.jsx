import { useState } from "react";
import "./Form.css";

export default function ChangePasswordForm() {
  const [oldPassword, setOldPassword] = useState("");
  const [newPassword, setNewPassword] = useState("");

  const handleSubmit = async (e) => {
    e.preventDefault();
    const token = localStorage.getItem("token");

    const res = await fetch("http://localhost:8000/api/user/change-password", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
      },
      body: JSON.stringify({
        oldPassword,
        newPassword,
      }),
    });

    if (res.ok) {
      alert("Password was changed.");
      setOldPassword("");
      setNewPassword("");
    } else {
      alert("Error while changing password.");
    }
  };

  return (
    <form onSubmit={handleSubmit} className="form-container">
      <h2>Change password</h2>
      <div className="input-pair">
        <input
          type="password"
          placeholder="OldPassword"
          value={oldPassword}
          onChange={(e) => setOldPassword(e.target.value)}
          required
        />
        <input
          type="password"
          placeholder="NewPassword"
          value={newPassword}
          onChange={(e) => setNewPassword(e.target.value)}
          required
        />
      </div>
      <button type="submit" className="submit-btn">Change</button>

    </form>
  );
}
