export async function getUsers() {
    const response = await fetch('http://127.0.0.1:8001/api/users');
    if (!response.ok) {
      throw new Error('Failed to fetch users');
    }
    return response.json();
  }