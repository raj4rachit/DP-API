// SmoothAreaChart.js
import React from 'react';
import { Bar, Doughnut } from 'react-chartjs-2';
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale } from 'chart.js';

// Register Chart.js components
ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale);

const SmoothAreaChart = () => {
    const data = {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets: [
            {
                label: 'Monthly Sales',
                data: [30, 45, 35, 50, 55, 60, 65], // Example data
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)', // Area color
                borderWidth: 2,
                tension: 0.4, // Smooth curves
                fill: true, // Enable area fill
            },
        ],
    };

    const options = {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                callbacks: {
                    label: (context) => `Sales: ${context.raw}`,
                },
            },
        },
        scales: {
            x: {
                grid: {
                    display: false,
                },
            },
            y: {
                grid: {
                    display: false,
                },
                beginAtZero: true,
            },
        },
    };

    return (
        <div className="w-full max-w-lg mx-auto p-4 bg-white shadow-md rounded-lg">
            <h2 className="text-lg font-semibold mb-4">Monthly Sales Over Time</h2>
            <Doughnut data={data} options={options} />
        </div>
    );
};

export default SmoothAreaChart;
