import { useEffect, useState, useRef } from 'react';
import { useParams } from 'react-router-dom';
import Navbar from '../components/Navbar';
import ErrorMessage from '../components/ErrorMessage';
import UserCard from '../components/UserCard';
import UserQuizzesList from '../components/lists/UserQuizzesList';
import useDynamicBodyHeight from '../hooks/useDynamicBodyHeight';
import './UserProfilePage.css';

export default function UserProfilePage() {
  const { id } = useParams();
  const [user, setUser] = useState(null);
  const [userExists, setUserExists] = useState(true);
  const token = localStorage.getItem('token');

  const navbarRef = useRef(null);
  const bodyRef = useRef(null);

  useDynamicBodyHeight(navbarRef, bodyRef);

  useEffect(() => {

  
    const fetchUser = async () => {
      try {
        const res = await fetch(`http://127.0.0.1:8000/api/user/${id}`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        if (res.status === 404) {
          setUserExists(false);
          return;
        }

        if (!res.ok) throw new Error('Data download error');

        const data = await res.json();
        setUser(data);
      } catch (err) {
        console.error(err);
        setUserExists(false);
      }
    };

    fetchUser();
  }, [id, token]);

  if (!userExists) {
    return (
      <div className='user-profile-page'>
        <Navbar />
        <ErrorMessage message="User not found" />
      </div>
    );
  }

  if (!user) {
    return (
      <div className='user-profile-page'>
        <Navbar />
        <ErrorMessage message="User not found" />
      </div>
    );
  }

  return (
    <div className='user-profile-page'>
      <div ref={navbarRef}>
        <Navbar />
      </div>
      <div className="user-profile-container" ref={bodyRef}>
        <UserCard username={user.username} />
        <UserQuizzesList userId={id} token={token} />
      </div>
    </div>
  );
}
