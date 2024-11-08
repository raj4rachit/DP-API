import { mergeClasses } from '@utils/classUtils';
import { cva } from 'class-variance-authority';
import React, { useMemo } from 'react';
import { HiChevronUpDown } from 'react-icons/hi2';

const Select = React.forwardRef((props, ref) => {
    const { size = 'medium', className = '', error = false, children, ...rest } = props;

    const SelectVariant = useMemo(
        () =>
            cva('rounded-md border outline-none w-full appearance-none', {
                variants: {
                    size: {
                        small: 'text-sm px-3 py-1.5',
                        medium: 'text-base px-4 py-2',
                        large: 'text-lg px-5 py-3',
                    },
                    error: {
                        true: 'text-red-500 border-red-500',
                        false: '',
                    },
                },
                defaultVariants: {
                    size: 'medium',
                    error: 'false',
                },
            }),
        [],
    );

    const computedClassNames = useMemo(
        () => mergeClasses(SelectVariant({ size, error }), className),
        [size, error, className, SelectVariant],
    );

    return (
        <div className="relative inline-block w-full">
            <select ref={ref} className={computedClassNames} {...rest}>
                {children}
            </select>

            <div className="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <HiChevronUpDown />
            </div>
        </div>
    );
});

export default Select;
