import "./Form.css";

export default function DeleteAccountSection() {
  const handleDelete = async () => {
    if (!window.confirm("Are you sure you want to delete your account?")) return;

    const token = localStorage.getItem("token");

    const res = await fetch("http://localhost:8000/api/user/delete", {
      method: "DELETE",
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });

    if (res.ok) {
      alert("Account deleted.");
      localStorage.removeItem("token");
      window.location.href = "/";
    } else {
      alert("Error while deleting an account.");
    }
  };

  return (
    <div className="form-container">
      <h2>Delete account</h2>
      <button onClick={handleDelete} className="del-btn">
        Delete
      </button>
    </div>
  );
}
