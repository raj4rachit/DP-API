import React from 'react';
import PropTypes from 'prop-types';

// Mapping Material UI style variants to Tailwind CSS classes
const variantClasses = {
    h1: 'text-5xl font-bold',
    h2: 'text-4xl font-bold',
    h3: 'text-3xl font-bold',
    h4: 'text-2xl font-semibold',
    h5: 'text-xl font-semibold',
    h6: 'text-lg font-semibold',
    body1: 'text-base',
    body2: 'text-sm',
    caption: 'text-xs',
    overline: 'text-xs uppercase tracking-widest',
};

const Typography = ({ component = 'p', variant = 'body1', className = '', children, ...rest }) => {
    const Component = component;

    const classes = `${variantClasses[variant]} ${className}`;

    return (
        <Component className={classes} {...rest}>
            {children}
        </Component>
    );
};

// Define propTypes to specify the expected types of props
Typography.propTypes = {
    component: PropTypes.oneOf(['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span', 'div']),
    variant: PropTypes.oneOf(['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'body1', 'body2', 'caption', 'overline']),
    className: PropTypes.string,
    children: PropTypes.any,
};

export default Typography;
