// src/components/BarChart.js
import React from 'react';
import ApexCharts from 'react-apexcharts';

const BarChart = () => {
    const options = {
        chart: {
            type: 'bar',
            height: 350,
            toolbar: {
                show: false, // Hide the entire toolbar
            },
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '50%',
                endingShape: 'rounded',
            },
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent'],
        },
        xaxis: {
            categories: ['Category 1', 'Category 2', 'Category 3', 'Category 4'],
            labels: {
                show: true,
            },
            axisBorder: {
                show: false, // Hide x-axis border
            },
            axisTicks: {
                show: false, // Hide x-axis ticks
            },
            lines: {
                show: false, // Hide x-axis lines
            },
            grid: {
                show: false, // Hide grid lines on x-axis
            },
        },
        yaxis: {
            labels: {
                show: true,
            },
            axisBorder: {
                show: false, // Hide y-axis border
            },
            axisTicks: {
                show: false, // Hide y-axis ticks
            },
            lines: {
                show: false, // Hide y-axis lines
            },
            grid: {
                show: false, // Hide grid lines on y-axis
            },
        },
        fill: {
            opacity: 0.8,
        },
        responsive: [
            {
                breakpoint: 480,
                options: {
                    chart: {
                        width: '100%',
                    },
                },
            },
        ],
    };

    const series = [
        {
            name: 'Series 1',
            data: [30, 40, 35, 45],
        },
        {
            name: 'Series 2',
            data: [30, 40, 35, 45],
        },
    ];

    return <ApexCharts options={options} series={series} type="bar" height={350} width={'100%'} />;
};

export default BarChart;
