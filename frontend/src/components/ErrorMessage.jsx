import './ErrorMessage.css';

export default function ErrorMessage({ code=404 , message }) {
  return (
    <div className="error-message">
      <h1>Error {code}</h1>
      <p>{message}</p>
    </div>
  );
}
