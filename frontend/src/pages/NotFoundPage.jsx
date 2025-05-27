import Navbar from '../components/Navbar';
import ErrorMessage from '../components/ErrorMessage';
import "./NotFoundPage.css";

export default function NotFoundPage() {
  return (
    <div className="error-page">
      <Navbar />
        <div className="error-msg">
            <ErrorMessage message="This page doesn't exist." />
        </div>
    </div>
  );
}