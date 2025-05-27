import './UserInfo.css';

export default function UserInfo({ username }) {
  return (
    <div className="user-info">
      <h2 className="user-info-title">Profil: {username}</h2>
    </div>
  );
}