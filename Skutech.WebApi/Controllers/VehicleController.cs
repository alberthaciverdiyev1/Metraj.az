using Microsoft.AspNetCore.Mvc;
using Skutech.BusinessLayer.Abstract;
using Skutech.EntityLayer;

namespace Skutech.WebApi.Controllers;

[Route("api/[controller]")]
[ApiController]
public class VehicleController : ControllerBase
{
    private readonly IVehicleService _vehicleService;

    public VehicleController(IVehicleService vehicleService)
    {
        _vehicleService = vehicleService;
    }

    [HttpGet]
    public IActionResult List()
    {
        var result = _vehicleService.TGetList();
        return Ok(result);
    }

    [HttpGet("{id}")]
    public IActionResult Details(int id)
    {
        var result = _vehicleService.TGetElementById(id);
        return Ok(result);
    }

    [HttpPost]
    public IActionResult Add(Vehicle vehicle)
    {
        _vehicleService.TInsert(vehicle);
        return Ok();
    }

    [HttpPut]
    public IActionResult Update(Vehicle vehicle)
    {
        _vehicleService.TUpdate(vehicle);
        return Ok();
    }

    [HttpDelete("{id}")]
    public IActionResult Delete(int id)
    {
        var value = _vehicleService.TGetElementById(id);
        if (value != null) _vehicleService.TDelete(value);
        return Ok();
    }
}