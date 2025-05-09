<h2>Facebook Ads Insights</h2>
<table border="1">
    <tr>
        <th>Campaign</th>
        <th>Impressions</th>
        <th>Reach</th>
        <th>Spend</th>
    </tr>
    @foreach($data['data'] as $item)
    <tr>
        <td>{{ $item['campaign_name'] ?? 'N/A' }}</td>
        <td>{{ $item['impressions'] }}</td>
        <td>{{ $item['reach'] }}</td>
        <td>{{ $item['spend'] }}</td>
    </tr>
    @endforeach
</table>
