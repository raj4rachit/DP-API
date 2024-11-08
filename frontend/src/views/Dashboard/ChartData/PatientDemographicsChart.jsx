// PatientDemographicsChart.js
import React, { useRef, useEffect } from 'react';
import { Pie } from 'react-chartjs-2';
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement } from 'chart.js';

// Register Chart.js components
ChartJS.register(Title, Tooltip, Legend, ArcElement);

const PatientDemographicsChart = () => {
    const chartRef = useRef(null); // Create a ref to hold the chart instance

    const data = {
        labels: ['Male', 'Female', 'Other'],
        datasets: [
            {
                label: 'Patient Gender Distribution',
                data: [45, 50, 5], // Example data
                backgroundColor: ['rgba(75, 192, 192, 0.6)', 'rgba(255, 99, 132, 0.6)', 'rgba(255, 159, 64, 0.6)'],
                borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)', 'rgba(255, 159, 64, 1)'],
                borderWidth: 1,
            },
        ],
    };

    useEffect(() => {
        // Create the chart instance
        const chart = chartRef.current;

        return () => {
            // Destroy the chart instance on cleanup
            if (chart && chart.chartInstance) {
                chart.chartInstance.destroy();
            }
        };
    }, []);

    return (
        <div className="w-full max-w-lg mx-auto p-4 bg-white shadow-md rounded-lg">
            <h2 className="text-lg font-semibold mb-4">Patient Demographics</h2>
            <Pie data={data} ref={chartRef} />
        </div>
    );
};

export default PatientDemographicsChart;
