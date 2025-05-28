import Navbar from "../components/Navbar";
import ChangePasswordForm from "../components/forms/ChangePasswordForm";
import DeleteAccountSection from "../components/forms/DeleteAccountSection";
import "./UserSettingsPage.css";

export default function UserSettingsPage() {
  return (
    <div className="user-settings-page">
      <Navbar />
      <div className="settings-content">
        <ChangePasswordForm />
        <DeleteAccountSection />
      </div>
    </div>
  );
}
