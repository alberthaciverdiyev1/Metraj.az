using System.ComponentModel.DataAnnotations;

namespace Skutech.EntityLayer;

public class Price
{
    [Key]
    public int Id { get; set; }
    [Required]
    public double Amount { get; set; }
    
    public int VehicleId { get; set; }
    public Vehicle? Vehicle { get; set; }
}