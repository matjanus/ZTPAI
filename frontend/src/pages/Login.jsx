import { Link } from "react-router-dom";

const Login = () => {
  return (
    <div>
      <h1>Logowanie</h1>
      <p>Podaj swoje dane, aby się zalogować.</p>
      <Link to="/">Powrót na stronę główną</Link>
    </div>
  );
};

export default Login;