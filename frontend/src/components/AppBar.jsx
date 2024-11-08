import { mergeClasses } from '@utils/classUtils';
import { cva } from 'class-variance-authority';

const AppBar = ({ children, open, mobile, className, onClose }) => {
    const AppBarVariant = cva('app-bar', {
        variants: {
            open: {
                true: ['w-60'],
                false: ['w-0', 'md:w-[47px]', 'md:hover:w-60'],
            },
            mobile: {
                true: ['absolute', 'top-0 left-0'],
                false: ['relative'],
            },
        },
        defaultVariants: {},
    });

    const AppNavVariant = cva('sidebar-nav', {
        variants: {
            open: {
                true: ['px-2'],
                false: ['px-0', 'md:px-2'],
            },
        },
        defaultVariants: {},
    });

    const AppBarBackgroundVariant = cva('fixed inset-0 bg-black transition-all duration-300 md:hidden', {
        variants: {
            open: {
                true: 'bg-opacity-50 z-30',
                false: 'bg-opacity-0 z-0',
            },
        },
        defaultVariants: {},
    });

    return (
        <>
            <aside className={mergeClasses(AppBarVariant({ open, mobile }), className)}>
                <nav className={mergeClasses(AppNavVariant({ open }))}>{children}</nav>
            </aside>
            <div className={mergeClasses(AppBarBackgroundVariant({ open }))} onClick={onClose} />
        </>
    );
};

export default AppBar;
