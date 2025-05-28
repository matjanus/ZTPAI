import RegisterForm from '../components/auth/RegisterForm';
import "./RegisterPage.css";


export default function RegisterPage() {
  return (
    <div className="register-page">
      <div className="register-form">
        <RegisterForm />
      </div>
    </div>
  );
}