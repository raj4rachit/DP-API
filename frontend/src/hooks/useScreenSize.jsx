import { useState, useEffect } from 'react';

const useScreenSize = () => {
    const [isMobile, setIsMobile] = useState(false);
    const [isDesktop, setIsDesktop] = useState(false);

    const checkScreenSize = () => {
        const width = window.innerWidth;
        if (width < 768) {
            setIsMobile(true);
            setIsDesktop(false);
        } else {
            setIsMobile(false);
            setIsDesktop(true);
        }
    };

    useEffect(() => {
        checkScreenSize();
        window.addEventListener('resize', checkScreenSize);

        return () => {
            window.removeEventListener('resize', checkScreenSize); // Cleanup
        };
    }, []);

    return { isMobile, isDesktop };
};

export default useScreenSize;
