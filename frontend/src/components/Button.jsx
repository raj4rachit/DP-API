import React, { useMemo } from 'react';
import PropTypes from 'prop-types';
import { mergeClasses } from '@utils/classUtils';
import { cva } from 'class-variance-authority';

// Button component
const Button = React.forwardRef((props, ref) => {
    const { size = 'medium', className = '', type = 'button', disabled = false, children, ...rest } = props;

    // Memoize the class names for performance optimization
    const ButtonVariant = useMemo(
        () =>
            cva('rounded-lg transition duration-200 ease-in-out', {
                variants: {
                    size: {
                        small: 'text-sm px-3 py-1.5',
                        medium: 'text-base px-4 py-2',
                        large: 'text-lg px-5 py-3',
                    },
                    disabled: {
                        true: 'disabled:bg-gray-500 disabled:cursor-not-allowed',
                        false: '',
                    },
                },
                defaultVariants: {
                    size: 'medium',
                    disabled: 'false',
                },
            }),
        [],
    );

    const computedClassNames = useMemo(
        () => mergeClasses(ButtonVariant({ size, disabled }), className),
        [size, className, disabled, ButtonVariant],
    );

    return (
        <button type={type} ref={ref} className={computedClassNames} disabled={disabled} {...rest}>
            {children}
        </button>
    );
});

Button.propTypes = {
    size: PropTypes.oneOf(['small', 'medium', 'large']),
    className: PropTypes.string,
};

export default Button;
