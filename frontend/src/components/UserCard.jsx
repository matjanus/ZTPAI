import "./UserCard.css";

export default function UserCard({ username, description }) {
  return (
    <div className="user-card">
      <h2>{username}</h2>
      <p>{description || "Brak opisu"}</p>
    </div>
  );
}