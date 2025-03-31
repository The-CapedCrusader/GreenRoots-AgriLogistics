import React, { useState } from 'react';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';

const CropInfo = () => {
  const [searchTerm, setSearchTerm] = useState('');
  const [cropData, setCropData] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const searchCrop = async () => {
    if (!searchTerm) return;
    
    setLoading(true);
    setError(null);
    
    try {
      const response = await fetch(
        `https://openfarm.cc/api/v1/crops?filter=${encodeURIComponent(searchTerm)}`
      );
      const data = await response.json();
      setCropData(data.data[0]);
    } catch (err) {
      setError('Failed to fetch crop information');
    } finally {
      setLoading(false);
    }
  };

  return (
    <Card className="w-full max-w-2xl mx-auto my-8">
      <CardHeader>
        <CardTitle>Crop Information Lookup</CardTitle>
      </CardHeader>
      <CardContent>
        <div className="flex gap-4 mb-6">
          <input
            type="text"
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            placeholder="Enter crop name (e.g., tomato)"
            className="flex-1 p-2 border rounded"
          />
          <button
            onClick={searchCrop}
            disabled={loading}
            className="px-4 py-2 bg-primary text-white rounded hover:bg-opacity-90 disabled:opacity-50"
          >
            {loading ? 'Searching...' : 'Search'}
          </button>
        </div>

        {error && (
          <div className="text-red-500 mb-4">
            {error}
          </div>
        )}

        {cropData && (
          <div className="space-y-4">
            <h3 className="text-xl font-bold">{cropData.attributes.name}</h3>
            <p className="text-gray-600">{cropData.attributes.description}</p>
            <div className="grid grid-cols-2 gap-4">
              <div>
                <h4 className="font-semibold">Sun Requirements</h4>
                <p>{cropData.attributes.sun_requirements || 'Not specified'}</p>
              </div>
              <div>
                <h4 className="font-semibold">Growing Degree Days</h4>
                <p>{cropData.attributes.growing_degree_days || 'Not specified'}</p>
              </div>
            </div>
          </div>
        )}
      </CardContent>
    </Card>
  );
};

export default CropInfo;