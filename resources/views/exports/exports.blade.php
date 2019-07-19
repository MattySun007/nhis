<table>
  <thead>
  <tr>
    <th>id</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Verification_no</th>
  </tr>
  </thead>
  <tbody>
  @foreach($results as $result)
    <tr>
      <td>{{ $result->id }}</td>
      <td>{{ $result->email }}</td>
      <td>{{ $result->phone }}</td>
      <td>{{ $result->verification_no }}</td>
    </tr>
  @endforeach
  </tbody>
</table>
