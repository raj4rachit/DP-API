// PatientVisitsChart.js
import React, { useRef, useEffect } from 'react';
import { Doughnut, Line } from 'react-chartjs-2';
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale } from 'chart.js';

// Register Chart.js components
ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale);

const PatientVisitsChart = () => {
    const chartRef = useRef(null); // Ref to access the chart instance

    const data = {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets: [
            {
                label: 'Patient Visits',
                data: [30, 45, 35, 50, 55, 60, 65], // Example data
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
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
                    label: (context) => `Visits: ${context.raw}`,
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
            },
        },
    };

    useEffect(() => {
        // Access the current chart instance
        const chartInstance = chartRef.current?.chartInstance;

        return () => {
            // Destroy the chart instance on cleanup
            if (chartInstance) {
                chartInstance.destroy();
            }
        };
    }, []);

    return (
        <div className="w-full max-w-lg mx-auto p-4 bg-white shadow-md rounded-lg">
            <h2 className="text-lg font-semibold mb-4">Patient Visits Over Time</h2>
            <Doughnut data={data} options={options} ref={chartRef} />
        </div>
    );
};

export default PatientVisitsChart;
