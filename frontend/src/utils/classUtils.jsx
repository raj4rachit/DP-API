// utils/classUtils.js
import { twMerge } from 'tailwind-merge';
import clsx from 'clsx';

/**
 * Combines Tailwind CSS classes with conditional logic and merges them.
 * @param {string | string[]} baseClasses - The base Tailwind classes or an array of classes.
 * @param {string | string[]} additionalClasses - Additional classes to be merged.
 * @returns {string} - The final combined and merged class names.
 */
export function mergeClasses(baseClasses, additionalClasses) {
    // Convert inputs to arrays if they are not already
    const baseClassArray = Array.isArray(baseClasses) ? baseClasses : [baseClasses];
    const additionalClassArray = Array.isArray(additionalClasses) ? additionalClasses : [additionalClasses];

    // Use clsx to combine base and additional classes conditionally
    const combinedClasses = clsx(...baseClassArray, ...additionalClassArray);

    // Use twMerge to handle Tailwind conflicts and merge classes
    return twMerge(combinedClasses);
}
