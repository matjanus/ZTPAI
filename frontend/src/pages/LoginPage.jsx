import { useLocation } from 'react-router-dom';
import LogoPanel from "../components/LogoPanel";
import LoginForm from "../components/auth/LoginForm";
import "./LoginPage.css";

export default function LoginPage() {
  const location = useLocation();
  const expired = new URLSearchParams(location.search).get("expired") === "true";

  return (
    <div className="login-page">
      <div className="login-left">
        <LogoPanel />
      </div>
      <div className="login-right">
        <LoginForm expired={expired} />
      </div>
    </div>
  );
}