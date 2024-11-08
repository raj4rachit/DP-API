import { useContext } from 'react';
import MenuContext from '@contexts/MenuContext';

const useMenu = () => {
    const context = useContext(MenuContext);
    if (!context) throw new Error('context must be use inside provider');
    return context;
};

export default useMenu;
