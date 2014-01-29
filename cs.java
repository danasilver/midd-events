import java.sql .* ;

public class cs {
	public static void main(String[] args) throws SQLException {
		//Connecting to the Database

		Connection connection = null;

		try {
			String driverName = "org.gjt.mm.mysql.Driver";

			Class.forName(driverName);

			String serverName = "panther.cs.middlebury.edu";
			String mydatabase = "achristman_College";
			String url = "jdbc:mysql://" + serverName + "/" + mydatabase;
			String username = "achristman";
			String password = "password";

			connection = DriverManager.getConnection(url, username, password);

		}
		catch (ClassNotFoundException e){
			System.out.println(e.getMessage());
		} catch (SQLException e) {
			System.out.println(e.getMessage());
		}


		// Execute commands

		Statement stmt = connection.createStatement ();

		String ins = "INSERT INTO cs1002Students (firstname, lastname, fave_breakfast_food, fave_breakfast_drink) values ('Will', 'Moore', 'BEC', 'OJ')";

		try {
			stmt.executeUpdate(ins);
			System.out.println(ins);
		}
		catch (SQLException sqlEx){
			System.out.println("Error: " + sqlEx.toString ());
		}

		String select = "SELECT * FROM cs1002Students WHERE firstname='Will' and lastname = 'Moore'";

		ResultSet rs=null;

		try {
			rs = stmt.executeQuery(select);
		} catch (SQLException sqlEx) {
			System.out.println("Could not execute query:" + sqlEx.toString ());
		}

		while (rs.next()) {
			String lastName = rs.getString("sname");
			System.out.println(lastName +"\n");
		}
	}

}


