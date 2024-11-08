import { LayoutDashboard, Settings, Users } from 'lucide-react';

const MenuItems = [
    {
        title: 'Dashboard',
        link: 'dashboard',
        icon: <LayoutDashboard strokeWidth={1.5} />,
    },
    // {
    //     title: 'Patients',
    //     link: 'patients',
    //     icon: <Users strokeWidth={1.5} />,
    // },
    {
        title: 'Settings',
        icon: <Settings strokeWidth={1.5} />,
        child: [
            {
                title: 'General Settings',
                link: 'settings/general',
            },
            {
                title: 'Master Settings',
                link: 'settings/master',
            },
            {
                title: 'System Settings',
                link: 'settings/system',
            },
        ],
    },
];

export default MenuItems;
