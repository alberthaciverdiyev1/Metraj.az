using Microsoft.AspNetCore.Mvc;
using Skutech.BusinessLayer.Abstract;
using Skutech.EntityLayer;

namespace Skutech.WebApi.Controllers;

[Route("api/[controller]")]
[ApiController]
public class VehicleModelConroller : ControllerBase
{
    private readonly IVehicleModelService _vehicleModelService;

    public VehicleModelConroller(IVehicleModelService vehicleModelService)
    {
        _vehicleModelService = vehicleModelService;
    }
    [HttpGet]
    public IActionResult List()
    {
        var result = _vehicleModelService.TGetList();
        return Ok(result);
    }

    [HttpGet("{id}")]
    public IActionResult Details(int id)
    {
        var result = _vehicleModelService.TGetElementById(id);
        return Ok(result);
    }

    [HttpPost]
    public IActionResult Add(VehicleModel vehicleModel)
    {
        _vehicleModelService.TInsert(vehicleModel);
        return Ok();
    }

    [HttpPut]
    public IActionResult Update(VehicleModel vehicleModel)
    {
        _vehicleModelService.TUpdate(vehicleModel);
        return Ok();
    }

    [HttpDelete("{id}")]
    public IActionResult Delete(int id)
    {
        var value = _vehicleModelService.TGetElementById(id);
        if (value != null) _vehicleModelService.TDelete(value);
        return Ok();
    }
}