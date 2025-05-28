import "./UserCard.css";

export default function UserCard({ username }) {
  return (
    <div className="user-card">
      <h2>{username}</h2>
    </div>
  );
}