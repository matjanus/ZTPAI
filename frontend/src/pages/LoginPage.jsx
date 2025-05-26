import LogoPanel from "../components/LogoPanel";
import LoginForm from "../components/LoginForm";
import "./LoginPage.css";

export default function LoginPage() {
  return (
    <div className="login-page">
      <div className="login-left">
         <LogoPanel />
      </div>
      <div className="login-right">
        <LoginForm />
      </div>
    </div>
  );
}