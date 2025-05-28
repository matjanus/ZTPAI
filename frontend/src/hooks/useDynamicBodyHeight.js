import { useEffect } from 'react';

export default function useDynamicBodyHeight(navbarRef, bodyRef) {
  useEffect(() => {
    const updateHeight = () => {
      if (navbarRef.current && bodyRef.current) {
        const navHeight = navbarRef.current.offsetHeight;
        bodyRef.current.style.height = `calc(100vh - ${navHeight}px)`;
      }
    };

    updateHeight(); // initial
    window.addEventListener('resize', updateHeight); // on resize

    return () => window.removeEventListener('resize', updateHeight);
  }, [navbarRef, bodyRef]);
}